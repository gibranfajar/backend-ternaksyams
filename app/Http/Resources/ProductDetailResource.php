<?php

namespace App\Http\Resources;

use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $flavours = Variant::with('flavour')
            ->where('product_id', $this->product_id)
            ->get()
            ->map(fn($variant) => [
                'id'            => $variant->flavour->id,
                'name'  => $variant->flavour->name,
            ]);

        $variants = Variant::with('sizes', 'flavour')->where('product_id', $this->product_id)->get();

        $related = Variant::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->take(4)
            ->get();

        return [
            'flavour_id'    => $this->flavour_id,
            'gizi_path'     => $this->product->gizi_path,
            'description'   => $this->product->description,
            'benefits'      => $this->product->benefits,
            'flavours'      => $flavours,
            'variants'      => VariantProductResource::collection($variants),
            'related'       => ProductResource::collection($related),
        ];
    }
}
