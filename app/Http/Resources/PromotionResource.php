<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => asset('storage/' . $this->thumbnail),
            'status' => $this->status,
            'is_popup' => $this->is_popup,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date
        ];
    }
}
