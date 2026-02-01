<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\VariantSize;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /*
     * Add product to cart
     * @param product_id
     * @param qty
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'user_id'    => 'nullable|integer',
            'session'    => 'nullable|string',
            'product_id' => 'required|integer',
            'qty'        => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $guestToken = $request->session ? trim($request->session) : null;

            // 1. Cari cart aktif
            $cart = Cart::when($request->user_id, function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            }, function ($q) use ($guestToken) {
                $q->where('session', $guestToken);
            })
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            // 2. Buat cart kalau belum ada
            if (! $cart) {
                $cart = Cart::create([
                    'user_id' => $request->user_id ?? null,
                    'session' => $request->user_id ? null : $guestToken,
                    'status'  => 'active',
                ]);
            } else {
                $cart->touch();
            }

            // 3. Ambil variant
            $variant = VariantSize::findOrFail($request->product_id);

            // 4. Ambil pricing (FLASH SALE / NORMAL)
            $pricing = $variant->getCartPricing();

            // 5. Cari cart item
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('variantsize_id', $variant->id)
                ->first();

            if ($cartItem) {
                $cartItem->update([
                    'qty' => $cartItem->qty + $request->qty,
                    'is_sale' => $pricing['is_sale'],
                    'is_flashsale' => $pricing['is_flashsale'],
                    'discount_type' => $pricing['discount_type'],
                    'discount' => $pricing['discount'],
                    'original_price' => $pricing['original_price'],
                    'price' => $pricing['price'],
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variantsize_id' => $variant->id,
                    'qty' => $request->qty,
                    'is_sale' => $pricing['is_sale'],
                    'is_flashsale' => $pricing['is_flashsale'],
                    'discount_type' => $pricing['discount_type'],
                    'discount' => $pricing['discount'],
                    'original_price' => $pricing['original_price'],
                    'price' => $pricing['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'cart_id' => $cart->id,
                    'session' => $cart->session,
                ],
                'message' => 'Product added to cart successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /*
     * Get cart
     */
    public function getCart(Request $request)
    {
        try {
            if ($request->user_id) {
                // Cari user
                $user = User::find($request->user_id);

                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found.',
                    ], 404);
                }

                // Ambil cart untuk user login
                $cartItems = CartItem::whereHas('cart', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->where('status', 'active');
                })->get();

                $data = CartResource::collection($cartItems);
            } else {
                // Ambil cart untuk guest
                $guestToken = $request->session;
                $cart = Cart::where('session', $guestToken)
                    ->where('status', 'active')
                    ->first();

                if ($cart) {
                    $cartItems = $cart->items; // pastikan relasi `items()` ada di model Cart
                    $data = CartResource::collection($cartItems);
                } else {
                    $data = [];
                }
            }

            return response()->json([
                'success' => true,
                'cart_id' => $cart->id ?? null,
                'data'    => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    /*
     * Increment cart
     */
    // public function increment(Request $request)
    // {
    //     try {
    //         $user = Auth::user();

    //         // Ambil cart
    //         if ($user) {
    //             $cart = CartItem::findorFail($request->id);
    //         } else {
    //             $guestToken = $request->cookie('guest_token');
    //             $cart = CartItem::whereHas('cart', function ($q) use ($guestToken) {
    //                 $q->where('session', $guestToken)->where('status', 'active');
    //             });
    //         }

    //         if ($cart) {
    //             $cart->update([
    //                 'qty' => $cart->qty + 1,
    //                 'price' => $cart->original_price * ($cart->qty + 1)
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message'    => 'Cart updated successfully.',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to get cart.',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    /*
     * Decrement cart
     */
    // public function decrement(Request $request)
    // {
    //     try {
    //         $user = Auth::user();

    //         // Ambil cart
    //         if ($user) {
    //             $cart = CartItem::findorFail($request->id);
    //         } else {
    //             $guestToken = $request->cookie('guest_token');
    //             $cart = CartItem::whereHas('cart', function ($q) use ($guestToken) {
    //                 $q->where('session', $guestToken)->where('status', 'active');
    //             });
    //         }

    //         if ($cart) {
    //             if ($cart->qty > 1) {
    //                 $cart->update([
    //                     'qty' => $cart->qty - 1,
    //                     'price' => $cart->original_price * ($cart->qty - 1)
    //                 ]);
    //             } else {
    //                 $cart->delete();
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message'    => 'Cart updated successfully.',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to get cart.',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    /*
     * Remove cart
     */
    // public function remove(Request $request, $id)
    // {
    //     try {
    //         $user = Auth::user();

    //         // Ambil cart
    //         if ($user) {
    //             $cart = CartItem::findorFail($id);
    //         } else {
    //             $guestToken = $request->cookie('guest_token');
    //             $cart = CartItem::whereHas('cart', function ($q) use ($guestToken) {
    //                 $q->where('session', $guestToken)->where('status', 'active');
    //             });
    //         }

    //         if ($cart) {
    //             $cart->delete();
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message'    => 'Cart deleted successfully.',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to get cart.',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
