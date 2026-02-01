<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantProductResource extends JsonResource
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
            'name'          => $this->name,
            'flavour_id'    => $this->flavour_id,
            'images'        => VariantProductImagesResource::collection($this->images),
            'sizes'         => VariantProductSizesResource::collection($this->sizes),
        ];
    }
}
