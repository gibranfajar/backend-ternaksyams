<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Article;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'slug'      => $this->slug,
            'category'  => $this->category->name,
            'excerpt'   => $this->excerpt,
            'content'   => $this->content,
            'thumbnail' => asset('storage/' . $this->thumbnail),
            'status'    => $this->status,

            // Ambil related articles tanpa recursion
            'related'   => Article::where('id', '!=', $this->id)
                ->latest()
                ->get(['id', 'title', 'slug', 'thumbnail'])
                ->map(function ($item) {
                    $item->thumbnail = asset('storage/' . $item->thumbnail);
                    return $item;
                }),
        ];
    }
}
