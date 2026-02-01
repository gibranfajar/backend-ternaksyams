<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantProductSizesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'size'          => $this->size->label,
            'price'         => $this->price,
            'discount'      => $this->discount,
            'price_discount' => $this->price_after_discount
        ];
    }
}
