<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $smallestSize = $this->sizes
            ->load('size')
            ->sortBy(fn($variant) => (int) $variant->size->label)
            ->first();

        return [
            'id'             => $this->id,
            'brand'          => $this->product->brand->brand ?? null, // tambahkan ->name
            'name'           => $this->name,
            'slug'           => $this->slug,
            'category'       => $this->category->name ?? null,
            'image'          => $this->images()->first()->image_path ?? null,
            'price'          => $smallestSize?->price,
            'discount'       => $smallestSize?->discount,
            'price_discount' => $smallestSize?->price_after_discount,
        ];
    }
}
