<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'                => $this->id,
            'product'           => $this->variantsize->variant->product->name,
            'variant'           => $this->variantsize->variant->flavour->name,
            'size'              => $this->variantsize->size->label . ' ' . $this->variantsize->size->unit,
            'quantity'          => $this->qty,
            'original_price'    => $this->original_price,
            'price'             => $this->price,
            'discount'          => $this->discount,
            'type_discount'     => $this->discount_type,
            'is_sale'           => $this->is_sale,
            'is_flashsale'      => $this->is_flashsale,
            'weight'            => intval($this->variantsize->size->label * $this->qty),
        ];
    }
}
