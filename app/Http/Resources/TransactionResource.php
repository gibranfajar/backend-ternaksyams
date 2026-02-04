<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'invoice'           => $this->invoice,
            'qty_item'          => $this->items->count(),
            'total'             => $this->total,
            'status'            => $this->status,
            'created_at'        => $this->created_at->format('d F Y | H:i:s'),
            'details'           => TransactionDetailResource::collection($this->items),
            'courier'           => strtoupper($this->shipping->shippingOption->expedition),
            'service'           => strtoupper($this->shipping->shippingOption->service),
            'cost'              => $this->shipping->shippingOption->cost,
            'address'           => $this->shipping->shippingInfo->address,
            'resi'              => $this->shipping->receipt_number,
            'payment_method'    => strtoupper($this->payment->method),
            'payment_status'    => $this->payment->status,
            'paid_at'           => $this->payment->paid_at,
            'payment_token'     => $this->payment->token
        ];
    }
}
