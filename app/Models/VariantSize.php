<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantSize extends Model
{
    protected $table = 'variant_sizes';

    protected $guarded = ['id'];

    // relasi ke table variant
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    // relasi ke table size
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getCartPricing()
    {
        $originalPrice = (int) $this->price;
        $discountPrice = (int) $this->price_after_discount;

        // DEFAULT (NO SALE)
        $isSale = false;
        $isFlashSale = false;
        $discountType = null;
        $discount = 0;
        $price = $originalPrice;

        /**
         * 1️⃣ DISKON PRODUK BIASA
         */
        if ($this->discount > 0) {
            $isSale = true;
            $discountType = 'percent';
            $discount = $this->discount;

            $price = $discountPrice;
        }

        /**
         * 2️⃣ FLASH SALE (OVERRIDE)
         */
        $flashSaleItem = FlashsaleItem::where('variantsize_id', $this->id)
            ->whereHas('flashsale', function ($q) {
                $q->where('status', 'ongoing')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
            ->first();

        if ($flashSaleItem) {
            $isFlashSale = true;
            $discountType = 'percent';
            $discount = $flashSaleItem->discount;

            $price = $originalPrice - ($originalPrice * $discount / 100);
        }

        return [
            'is_sale' => $isSale,
            'is_flashsale' => $isFlashSale,
            'discount_type' => $discountType,
            'discount' => (int) $discount,
            'original_price' => $originalPrice,
            'price' => (int) max($price, 0),
        ];
    }
}
