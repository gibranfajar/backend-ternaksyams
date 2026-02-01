<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'title' => $this->flashSale->title,
            'slug' => $this->flashSale->slug,
            'description' => $this->flashSale->description,
            'status' => $this->flashSale->status,
            'start_date' => $this->flashSale->start_date,
            'end_date' => $this->flashSale->end_date,

            'variant_id' => $this->variant_id,
            'variant_name' => $this->variant->name,
            'variant_slug' => $this->variant->slug,
            'product_name' => $this->variant->product->name,
            'brand' => $this->variant->product->brand->brand ?? null,
            'category' => $this->variant->category->name ?? null,

            'size' => $this->variantSize->size->label,

            'price_original' => $this->variantSize->price,
            'price_flash_sale' => $this->flashsale_price,
            'discount' => $this->discount,

            'stock_flash_sale' => $this->stock,

            'image' => optional($this->variant->images->first())->image_path,
        ];
    }
}
