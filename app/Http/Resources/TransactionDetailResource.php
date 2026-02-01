<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'variant'           => $this->variant,
            'size'              => $this->size,
            'original_price'    => $this->original_price,
            'discount'          => $this->discount,
            'price'             => $this->price,
            'qty'               => $this->qty,
            'total'             => $this->total,
        ];
    }
}
