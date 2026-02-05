<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipper;
use App\Models\Shipping;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    protected $baseUrlKomerce;

    public function __construct()
    {
        $this->baseUrlKomerce = env('RAJAONGKIR_URL_KOMSHIP');
    }

    public function index()
    {
        $orders = Order::with('voucher')->orderBy('id', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    public function invoice($id)
    {
        $order = Order::findOrFail($id)->load('items');

        $pdf = Pdf::loadView('orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        $filename = str_replace(['/', '\\'], '-', $order->invoice);

        return $pdf->stream("Invoice-{$filename}.pdf");
    }

    /**
     * Display a listing of the resource.
     */
    public function pickup()
    {
        $orders = Order::where('status', 'processing')
            ->whereHas('shipping', function ($query) {
                $query->whereNotNull('order_number')
                    ->where('order_number', '!=', '');
            })
            ->orderByDesc('id')
            ->get();

        return view('orders.pickup', compact('orders'));
    }

    /**
     * Print shipping labels
     */
    public function printLabel()
    {
        $orders = Order::where('status', 'packaging')
            ->whereHas('shipping', function ($query) {
                $query->whereNotNull('order_number')
                    ->where('order_number', '!=', '');
            })
            ->orderByDesc('id')
            ->get();

        return view('orders.printLabel', compact('orders'));
    }


    public function labelStore(Request $request)
    {
        $request->validate([
            'selected_orders' => 'required|array|min:1',
        ]);

        $orderNos = implode(',', $request->selected_orders);

        $query = http_build_query([
            'page' => 'page_6',
            'order_no' => $orderNos,
        ]);

        try {
            $response = Http::withHeaders([
                'Accept'    => 'application/json',
                'x-api-key' => env('RAJAONGKIR_DELIVERY_API_KEY'),
            ])->post("{$this->baseUrlKomerce}/order/api/v1/orders/print-label?$query");

            $data = $response->json();

            if ($response->failed() || ($data['meta']['status'] ?? '') === 'error') {
                return response()->json([
                    'success' => false,
                    'message' => $data['meta']['message'] ?? 'Gagal generate label',
                    'detail'  => $data['data'] ?? '',
                ], 422);
            }

            $pdfPath = $data['data']['path'] ?? null;
            if ($pdfPath) {
                $url = "{$this->baseUrlKomerce}/order" . $pdfPath;
                return response()->json(['success' => true, 'url' => $url]);
            }

            return response()->json(['success' => false, 'message' => 'File path tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Request order to komship
     */
    public function orderRequest(Order $order)
    {
        try {
            // check shipper data
            $shipper = Shipper::first();
            if (!$shipper) {
                return back()->with('error', 'Data pengiriman toko belum lengkap untuk order ini silahkan isi terlebih dahulu di menu pengaturan.');
            }

            // Pastikan relasi lengkap
            if (!$order->shipping || !$order->shipping->shippingInfo || !$order->shipping->shippingOption) {
                return back()->with('error', 'Data pengiriman belum lengkap untuk order ini.');
            }

            // Bangun item details + hitung dari sumber yang sama
            $itemDetailsKomship = $order->items->map(function ($item) {
                // harga setelah diskon (JANGAN round dulu)
                $priceAfterDiscount = ($item->original_price * (100 - $item->discount)) / 100;

                // subtotal = harga * qty, baru di-round SEKALI
                $subtotal = intval(round($priceAfterDiscount * $item->qty));

                return [
                    "product_name" => $item->name,
                    "product_variant_name" => ($item->variant ?? '-') . ' - ' . ($item->size ?? '-'),
                    "product_price" => intval(round($priceAfterDiscount)), // untuk tampilan, integer
                    "product_weight" => intval(optional($item->variantSize->size)->label ?? 0),
                    "product_width" => 0,
                    "product_height" => 0,
                    "product_length" => 0,
                    "qty" => $item->qty,
                    "subtotal" => $subtotal,
                ];
            })->toArray();

            // Ambil data shipping
            $shippingInfo = $order->shipping->shippingInfo;
            $shippingOption = $order->shipping->shippingOption;

            $totalItems = collect($itemDetailsKomship)->sum('subtotal');

            $shippingCost     = intval($shippingOption->cost);
            $shippingCashback = intval($shippingOption->shipping_cashback);
            $serviceFee       = 0;
            $additionalCost   = 0;

            $grandTotal = $totalItems + $shippingCost + $serviceFee + $additionalCost;

            // Request ke Komship
            $response = Http::withHeaders([
                'x-api-key' => env('RAJAONGKIR_DELIVERY_API_KEY'),
                'Accept' => 'application/json',
            ])->post("{$this->baseUrlKomerce}/order/api/v1/orders/store", [
                "order_date" => now()->toDateTimeString(),
                "brand_name" => $shipper->brand_name,
                "shipper_name" => $shipper->shipper_name,
                "shipper_phone" => $shipper->shipper_phone,
                "shipper_destination_id" => intval($shipper->subdistrict),
                "shipper_address" => $shipper->shipper_address,
                "shipper_email" => $shipper->shipper_email,
                "receiver_name" => $shippingInfo->name,
                "receiver_phone" => $shippingInfo->phone,
                "receiver_destination_id" => $shippingInfo->destination_id,
                "receiver_address" => $shippingInfo->address,
                "receiver_email" => $shippingInfo->email,
                "shipping" => strtoupper($shippingOption->expedition),
                "shipping_type" => strtoupper($shippingOption->service),
                "payment_method" => "BANK TRANSFER",
                "shipping_cost" => $shippingCost,
                "shipping_cashback" => $shippingCashback,
                "service_fee" => $serviceFee,
                "additional_cost" => $additionalCost,
                "grand_total" => $grandTotal,
                "cod_value" => 0,
                "insurance_value" => 0,
                "order_details" => $itemDetailsKomship,
            ]);

            // Cek hasil response
            if ($response->failed()) {
                Log::error('Komship API Error', [
                    'order_id' => $order->id,
                    'response' => $response->json(),
                ]);
                return back()->with('error', 'Gagal mengirim data ke Komship. Silakan coba lagi.');
            }

            // Ambil data response
            $result = data_get($response->json(), 'data');
            if (!$result || !isset($result['order_no'])) {
                return back()->with('error', 'Response dari Komship tidak valid.');
            }

            // Update shipping order number
            $order->shipping()->update([
                'order_number' => $result['order_no'],
            ]);

            return redirect()->route('orders.index')->with('success', 'Order berhasil dikirim ke Komship.');
        } catch (\Throwable $e) {
            Log::error('Komship Request Error', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Terjadi kesalahan internal: ' . $e->getMessage());
        }
    }


    /**
     * Store pickup order
     */
    public function pickupStore(Request $request)
    {
        $request->validate([
            'pickup_date'    => 'required|date',
            'pickup_time'    => 'required',
            'pickup_vehicle' => 'required|in:motor,mobil,truck',
            'orders'         => 'required|array|min:1',
        ]);

        // mapping order_no sesuai format API
        $orders = array_map(fn($order) => ['order_no' => $order], $request->orders);

        $payload = [
            "pickup_date"    => $request->pickup_date,
            "pickup_time"    => $request->pickup_time,
            "pickup_vehicle" => $request->pickup_vehicle,
            "orders"         => $orders,
        ];

        // kirim request JSON ke API Komerce
        $response = Http::withHeaders([
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'x-api-key'    => env('RAJAONGKIR_DELIVERY_API_KEY'),
        ])->post("{$this->baseUrlKomerce}/order/api/v1/pickup/request", $payload);

        $data = $response->json();
        $message = $data['meta']['message'] ?? 'Unknown error';

        // handle error 400
        if ($response->status() == 400) {
            return back()->with('error', 'Failed to pickup order: ' . $message);
        }

        if ($response->successful()) {
            $shippingData = $data['data'] ?? [];

            foreach ($shippingData as $item) {
                $orderNo = $item['order_no'] ?? null;
                $awb     = $item['awb'] ?? null;

                if ($orderNo && $awb) {
                    // update shipping berdasarkan order_no
                    $shipping = Shipping::where('order_number', $orderNo)->first();
                    if ($shipping) {
                        $shipping->update([
                            'status'         => 'sent',
                            'shipped_at'     => now(),
                            'receipt_number' => $awb,
                        ]);

                        // update order terkait
                        $shipping->order()->update([
                            'status' => 'packaging',
                        ]);
                    }
                }
            }

            return redirect()->route('orders.index')->with('success', 'Order pickup successfully.');
        }


        return back()->with('error', 'Failed to pickup order: ' . ($message ?? $response->body()));
    }
}
