<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Models\Voucher;
use App\Models\VoucherProduct;
use App\Models\VoucherUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::all();
        return view('vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('variants')->get();
        $users = User::all();
        return view('vouchers.create', compact('products', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $startDate = \Carbon\Carbon::parse($request->start_date);
                $status = $startDate->lessThanOrEqualTo(now())
                    ? 'active'
                    : 'draft';

                // Simpan voucher
                $voucher = Voucher::create([
                    'code' => $request->code,
                    'quota' => $request->quota,
                    'type' => $request->type,
                    'target' => $request->target,
                    'amount_type' => $request->amount_type,
                    'amount' => $request->amount,
                    'max_value' => $request->max_value,
                    'min_transaction_value' => $request->min_transaction,
                    'limit' => $request->limit,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'status' => $status
                ]);

                if ($request->has('selected_products')) {
                    $variants = Variant::whereIn('product_id', $request->selected_products)
                        ->with('sizes')
                        ->get();

                    foreach ($variants as $variant) {
                        foreach ($variant->sizes as $variantsize) {
                            VoucherProduct::create([
                                'voucher_id' => $voucher->id,
                                'variantsize_id' => $variantsize->id
                            ]);
                        }
                    }
                }


                if ($request->has('selected_users')) {
                    $users = User::whereIn('id', $request->selected_users)->get();
                    foreach ($users as $user) {
                        VoucherUser::create([
                            'voucher_id' => $voucher->id,
                            'user_id' => $user->id,
                            'title' => $request->title,
                            'thumbnail' => $request->file('thumbnail')->store('vouchers', 'public'),
                            'content' => $request->content
                        ]);
                    }
                }
            });

            return redirect()->route('vouchers.index')->with('success', 'Voucher created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek kode error SQLSTATE
            if ($e->getCode() === '23505') {
                return redirect()->route('vouchers.index')->with('error', 'Voucher code already exists.');
            }

            return redirect()->route('vouchers.index')->with('error', 'Failed to create voucher.');
        } catch (\Throwable $th) {
            return redirect()->route('vouchers.index')->with('error', 'Failed to create voucher.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        // Load relasi yang dibutuhkan
        $voucher->load('variantSizes.variant.product', 'users');

        // Ambil semua product dari variantSizes (unik, tanpa duplikat)
        $productVouchers = $voucher->variantSizes
            ->map(function ($vs) {
                return $vs->variant?->product;
            })
            ->filter()          // buang null
            ->unique('id')      // hilangkan duplikat product
            ->values();

        // Ambil first product kalau masih mau dipakai buat logic di view
        $firstProduct = $productVouchers->first();

        // Ambil semua products dan users untuk form edit
        $products = Product::with('variants')->get();
        $users = User::all();

        // Ambil voucherContent untuk user pertama (jika ada)
        $voucherContent = VoucherUser::where('voucher_id', $voucher->id)->first();

        return view('vouchers.edit', compact(
            'voucher',
            'products',
            'users',
            'voucherContent',
            'firstProduct',
            'productVouchers'
        ));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        try {
            DB::transaction(function () use ($request, $voucher) {
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $status = $startDate->lessThanOrEqualTo(now())
                    ? 'active'
                    : 'draft';

                // Update voucher
                $voucher->update([
                    'code' => $request->code,
                    'quota' => $request->quota,
                    'type' => $request->type,
                    'target' => $request->target,
                    'amount_type' => $request->amount_type,
                    'amount' => $request->amount,
                    'max_value' => $request->max_value,
                    'min_transaction_value' => $request->min_transaction,
                    'limit' => $request->limit,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'status' => $status
                ]);

                // Update voucher products
                if ($request->has('selected_products')) {
                    // Hapus relasi lama
                    VoucherProduct::where('voucher_id', $voucher->id)->delete();

                    $variants = Variant::whereIn('product_id', $request->selected_products)
                        ->with('sizes')
                        ->get();

                    foreach ($variants as $variant) {
                        foreach ($variant->sizes as $variantsize) {
                            VoucherProduct::create([
                                'voucher_id' => $voucher->id,
                                'variantsize_id' => $variantsize->id
                            ]);
                        }
                    }
                }

                // Update voucher users
                if ($request->has('selected_users') && is_array($request->selected_users)) {
                    // Ambil salah satu thumbnail yang sudah ada untuk voucher ini
                    $existingThumbnail = VoucherUser::where('voucher_id', $voucher->id)
                        ->whereNotNull('thumbnail')
                        ->value('thumbnail');

                    foreach ($request->selected_users as $userId) {
                        $voucherUser = VoucherUser::where('voucher_id', $voucher->id)
                            ->where('user_id', $userId)
                            ->first();

                        $data = [
                            'voucher_id' => $voucher->id,
                            'user_id'    => $userId,
                            'title'      => $request->title,
                            'content'    => $request->content,
                        ];

                        if ($request->hasFile('thumbnail')) {
                            // hapus file lama jika ada
                            if ($voucherUser && $voucherUser->thumbnail && Storage::disk('public')->exists($voucherUser->thumbnail)) {
                                Storage::disk('public')->delete($voucherUser->thumbnail);
                            }
                            $data['thumbnail'] = $request->file('thumbnail')->store('vouchers', 'public');
                        } else {
                            // jika voucherUser lama → pakai thumbnail lama
                            // jika voucherUser baru → pakai salah satu thumbnail yang sudah ada
                            $data['thumbnail'] = $voucherUser ? $voucherUser->thumbnail : $existingThumbnail;
                        }

                        if (!$data['thumbnail']) {
                            throw new \Exception("Thumbnail harus ada untuk voucherUser ID: $userId");
                        }

                        if ($voucherUser) {
                            $voucherUser->update($data);
                        } else {
                            VoucherUser::create($data);
                        }
                    }
                }
            });

            return redirect()->route('vouchers.index')->with('success', 'Voucher updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek kode error SQLSTATE
            if ($e->getCode() === '23505') {
                return redirect()->route('vouchers.index')->with('error', 'Voucher code already exists.');
            }

            return redirect()->route('vouchers.index')->with('error', 'Failed to create voucher.');
        } catch (\Throwable $th) {
            return redirect()->route('vouchers.index')->with('error', 'Failed to create voucher.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
