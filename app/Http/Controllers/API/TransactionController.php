<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Mail\OrderInvoiceMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\ShippingInformation;
use App\Models\ShippingOption;
use App\Models\VariantSize;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Midtrans\Notification;

class TransactionController extends Controller
{

    protected $baseUrlKomship;
    protected $baseUrlMidtrans;

    public function __construct()
    {
        $this->baseUrlKomship = env('RAJAONGKIR_URL_KOMSHIP');
        $this->baseUrlMidtrans = env('MIDTRANS_URL');
    }

    /**
     * Create transaction.
     */
    public function createTransaction(Request $request)
    {
        DB::beginTransaction();

        try {
            // === 1️⃣ Ambil cart aktif ===
            $cart = Cart::with('items.variantsize.variant.product', 'items.variantsize.size')
                ->where('id', $request->cart_id)
                ->where('status', 'active')
                ->first();

            if (!$cart) {
                return response()->json(['message' => 'Cart not found'], 404);
            }

            // === 2️⃣ Generate invoice ===
            $invoice = 'INV-TS/' . now()->format('ymd') . '/' . rand(1000, 9999);

            // === 3️⃣ Simpan shipping option ===
            $shippingOption = ShippingOption::create([
                'expedition' => $request->courier,
                'service'    => $request->service,
                'cost'       => $request->cost,
                'etd'        => $request->etd ?? '-',
                'weight'     => $request->total_weight,
                'shipping_cashback' => $request->shipping_cashback,
                'grand_total' => $request->total
            ]);

            // === 4️⃣ Simpan shipping information ===
            $shippingInfo = ShippingInformation::create([
                'name'        => $request->name,
                'phone'       => $request->phone,
                'email'       => $request->email,
                'address'     => $request->address,
                'province'    => $request->province,
                'city'        => $request->city,
                'district'    => $request->district,
                'postal_code' => $request->postal_code,
                'destination_id' => $request->destination_id
            ]);

            // === 5️⃣ Buat order ===
            $order = Order::create([
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'session' => $cart->session,
                'invoice' => $invoice,
                'total'   => $request->total,
                'note'    => $request->note
            ]);

            // === 6️⃣ Simpan item order ===
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'variantsize_id' => $item->variantsize->id,
                    'name'           => $item->variantsize->variant->product->name,
                    'variant'        => $item->variantsize->variant->flavour->name,
                    'size'           => $item->variantsize->size->label . ' ' . $item->variantsize->size->unit,
                    'original_price' => $item->original_price,
                    'discount_type'  => $item->discount_type,
                    'discount'       => $item->discount,
                    'price'          => $item->price,
                    'qty'            => $item->qty,
                    'total'          => $item->price * $item->qty,
                    'is_sale'        => $item->is_sale,
                    'is_flashsale'   => $item->is_flashsale,
                ]);
            }

            // === 7️⃣ Midtrans config ===
            Config::$serverKey    = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            // === 8️⃣ Kumpulin item detail untuk Midtrans ===
            $itemDetails = [];
            foreach ($order->items as $orderItem) {
                $itemDetails[] = [
                    'id'       => $orderItem->id,
                    'price'    => (int) $orderItem->price,
                    'quantity' => (int) $orderItem->qty,
                    'name'     => $orderItem->name . ' - ' . $orderItem->variant . ' (' . $orderItem->size . ')',
                ];
            }

            // Tambahkan ongkir
            $itemDetails[] = [
                'id'       => 'shipping',
                'price'    => (int) $shippingOption->cost,
                'quantity' => 1,
                'name'     => 'Shipping - ' . $shippingOption->expedition . ' ' . $shippingOption->service,
            ];

            // === 9️⃣ Voucher (jika ada) ===
            $discountAmount = 0;

            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', $request->voucher_code)
                    ->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();

                if (!$voucher) {
                    return response()->json(['message' => 'Voucher not found or inactive'], 404);
                }

                // Cek kuota total voucher
                $usedCount = VoucherUsage::where('voucher_id', $voucher->id)->count();
                if ($usedCount >= $voucher->quota) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Voucher quota is full',
                    ], 400);
                }

                // Tentukan target pengurangan berdasarkan tipe voucher
                $targetAmount = 0;
                switch ($voucher->type) {
                    case 'transaction':
                    case 'product': // bisa dikembangkan lebih spesifik untuk product tertentu
                        $targetAmount = max($order->total - $shippingOption->cost, 0);
                        break;
                    case 'shipping':
                        $targetAmount = max($shippingOption->cost, 0);
                        break;
                }

                // Hitung diskon
                if ($voucher->amount_type === 'percent') {
                    $discountAmount = ($voucher->amount / 100) * $targetAmount;

                    // Batasi dengan max_value jika ada
                    if ($voucher->max_value) {
                        $discountAmount = min($discountAmount, $voucher->max_value);
                    }
                } else { // amount_type = value
                    $discountAmount = min($voucher->amount, $targetAmount);
                }

                // Terapkan diskon ke order
                switch ($voucher->type) {
                    case 'transaction':
                    case 'product':
                        $orderSubtotal = max($order->total - $shippingOption->cost, 0);
                        $orderSubtotal -= $discountAmount;
                        $order->update([
                            'total' => max($orderSubtotal + $shippingOption->cost, 0),
                        ]);
                        break;
                    case 'shipping':
                        $newShippingCost = max($shippingOption->cost - $discountAmount, 0);
                        $order->update([
                            'total' => max(($order->total - $shippingOption->cost) + $newShippingCost, 0),
                        ]);
                        break;
                }

                // Tambahkan diskon ke itemDetails untuk Midtrans
                $itemDetails[] = [
                    'id'       => 'voucher',
                    'price'    => -(int) $discountAmount,
                    'quantity' => 1,
                    'name'     => 'Voucher Discount (' . $voucher->code . ')',
                ];

                // Simpan penggunaan voucher
                VoucherUsage::create([
                    'order_id'   => $order->id,
                    'voucher_id' => $voucher->id,
                    'user_id'    => $cart->user_id,
                    'session'    => $cart->session,
                    'used_at'    => now(),
                    'amount'     => $discountAmount,
                ]);
            }



            // === 🔟 Buat parameter Midtrans ===
            $params = [
                'transaction_details' => [
                    'order_id'     => $order->invoice,
                    'gross_amount' => (int) $order->total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $request->name,
                    'phone'      => $request->phone,
                    'email'      => $request->email ?? 'guest@example.com',
                    'shipping_address' => [
                        'first_name' => $request->name,
                        'phone'      => $request->phone,
                        'address'    => $request->address,
                        'city'       => $request->city,
                        'postal_code' => $request->postal_code,
                    ]
                ]
            ];

            // === 11️⃣ Dapetin Snap Token ===
            $snapToken = Snap::getSnapToken($params);

            // === 12️⃣ Simpan payment ===
            Payment::create([
                'order_id' => $order->id,
                'method'   => 'midtrans',
                'token'    => $snapToken
            ]);

            // === 13️⃣ Simpan shipping ===
            Shipping::create([
                'order_id'                => $order->id,
                'shipping_options_id'     => $shippingOption->id,
                'shipping_information_id' => $shippingInfo->id,
                'weight'                  => $request->total_weight
            ]);

            // === 14️⃣ Update cart & stok ===
            $cart->update(['status' => 'ordered']);

            foreach ($order->items as $orderItem) {
                VariantSize::where('id', $orderItem->variantsize_id)
                    ->decrement('stock', $orderItem->qty);
            }

            DB::commit();

            // === ✅ Reload order dengan relasi lengkap sebelum kirim email ===
            $order->load([
                'items',
                'shipping.shippingOption',
                'shipping.shippingInfo'
            ]);

            // Buat link pembayaran Midtrans
            $paymentUrl = $this->baseUrlMidtrans . $snapToken;

            // Kirim email invoice
            Mail::to($request->email)->queue(new OrderInvoiceMail($order, $paymentUrl));

            // === ✅ Return response ===
            return response()->json([
                'message'    => 'success',
                'invoice'    => $invoice,
                'snap_token' => $snapToken,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * List Transaction By User.
     */
    public function getTransactionUser()
    {
        $orders = Order::where('user_id', Auth::user()->id)->get();

        $data = TransactionResource::collection($orders);

        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * Callback Midtrans
     */
    public function callback(Request $request)
    {
        // Set Midtrans config
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type        = $notif->payment_type;
        $orderId     = $notif->order_id;
        $fraud       = $notif->fraud_status;

        // Cari order berdasarkan invoice
        $order = Order::where('invoice', $orderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = Payment::where('order_id', $order->id)->first();
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Tentukan payment method
        $paymentMethod = $this->getPaymentMethod($notif);

        // Update payment log
        $payment->update([
            'status'    => $transaction,
            'method'    => $paymentMethod,
            'paid_at'   => in_array($transaction, ['settlement', 'capture']) ? now() : null,
        ]);

        // Handle status order
        switch ($transaction) {
            case 'capture':
                if ($type == 'credit_card') {
                    $order->update([
                        'status' => $fraud == 'challenge' ? 'challenge' : 'paid'
                    ]);
                }
                break;

            case 'settlement':
                $order->update(['status' => 'processing']);
                break;

            case 'pending':
                $order->update(['status' => 'pending']);
                break;

            case 'deny':
                $order->update(['status' => 'denied']);
                break;

            case 'expire':
                $order->update(['status' => 'expired']);
                break;

            case 'cancel':
                $order->update(['status' => 'cancelled']);
                break;
        }

        return response()->json(['message' => 'Callback processed']);
    }

    /**
     * Normalisasi payment method dari response Midtrans
     */
    private function getPaymentMethod($notif)
    {
        $type = $notif->payment_type;

        if ($type == 'bank_transfer') {
            $vaNumbers = $notif->va_numbers ?? [];
            if (!empty($vaNumbers)) {
                return strtoupper($vaNumbers[0]->bank); // BCA, MANDIRI, dll
            }
            return 'BANK_TRANSFER';
        } elseif ($type == 'cstore') {
            return ucfirst($notif->store ?? 'CSTORE'); // Alfamart, Indomaret
        } elseif ($type == 'qris') {
            return 'QRIS';
        } else {
            return strtoupper($type); // fallback
        }
    }

    /**
     * history tracking order
     */
    public function trackOrder(Request $request)
    {
        $courier = $request->query('courier'); // contoh: ninja
        $resi = $request->query('resi');       // contoh: KOMERKOM87623202511122109

        if (!$courier || !$resi) {
            return response()->json(['message' => 'Courier or resi is required'], 400);
        }

        // Optional: cek di DB dulu
        $order = Order::whereHas('shipping', function ($q) use ($resi) {
            $q->where('receipt_number', $resi);
        })->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Request ke Komship API
        $response = Http::withHeaders([
            'x-api-key' => env('RAJAONGKIR_DELIVERY_API_KEY')
        ])->get("{$this->baseUrlKomship}/order/api/v1/orders/history-airway-bill", [
            'shipping' => $courier,
            'airway_bill' => $resi,
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to fetch tracking info',
                'error' => $response->json()
            ], 500);
        }

        return response()->json([
            'order' => $order,
            'tracking' => $response->json()
        ]);
    }
}
