<?php

namespace App\Http\Resources;

use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $now = now();

        /**
         * 🔥 1. Semua flavour dalam 1 product
         */
        $flavours = Variant::with('flavour')
            ->where('product_id', $this->product_id)
            ->get()
            ->unique('flavour_id')
            ->map(fn($variant) => [
                'id'   => $variant->flavour->id,
                'name' => $variant->flavour->name,
            ])
            ->values();


        /**
         * 🔥 2. Ambil hanya variant yang sedang flash sale
         */
        $variants = Variant::with([
            'sizes.size',
            'flashSaleItems.flashSale',
        ])
            ->where('product_id', $this->product_id)

            // ❗ FILTER VARIANT ADA FLASH SALE
            ->whereHas('flashSaleItems.flashSale', function ($q) use ($now) {
                $q->where('status', 'ongoing')
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            })

            ->get()
            ->map(function ($variant) use ($now) {

                /**
                 * 🔥 2B. FILTER SIZE – hanya size yang flash sale
                 */
                $sizes = $variant->sizes
                    ->map(function ($size) use ($variant, $now) {

                        // Cari flash sale item untuk size ini
                        $flashItem = $variant->flashSaleItems
                            ->first(
                                fn($item) =>
                                $item->variantsize_id === $size->id &&
                                    $item->flashSale &&
                                    $item->flashSale->status === 'ongoing' &&
                                    $item->flashSale->start_date <= $now &&
                                    $item->flashSale->end_date >= $now
                            );

                        // ❗ Jika size ini tidak flash sale → buang
                        if (!$flashItem) return null;

                        return [
                            'variant_size_id' => $size->id,
                            'size'            => $size->size->label,

                            'price_original'   => $size->price,
                            'price_flash_sale' => $flashItem->flashsale_price,
                            'discount'         => $flashItem->discount,

                            'final_price'      => $flashItem->flashsale_price,
                            'stock_flash_sale' => $flashItem->stock,
                            'is_flash_sale'    => true,
                        ];
                    })
                    ->filter()   // ❗ hapus size null (non flash sale)
                    ->values();  // reset index

                return [
                    'variant_id'   => $variant->id,
                    'variant_name' => $variant->name,
                    'variant_slug' => $variant->slug,
                    'sizes'        => $sizes,
                ];
            })
            ->filter(fn($variant) => $variant['sizes']->count() > 0) // ❗ pastikan variant punya size flash sale
            ->values();


        /**
         * 🔥 3. Related product (tidak berubah)
         */
        $related = Variant::where('category_id', $this->category_id)
            ->where('product_id', '!=', $this->product_id)
            ->take(4)
            ->get();


        /**
         * 🔥 4. Final Response
         */
        return [
            'product_name' => $this->product->name,
            'brand'        => $this->product->brand->brand ?? null,

            'gizi_path'   => $this->product->gizi_path,
            'description' => $this->product->description,
            'benefits'    => $this->product->benefits,

            'flavours' => $flavours,
            'variants' => $variants, // 🔥 hanya variant + size flash sale

            'related'  => ProductResource::collection($related),
        ];
    }
}
