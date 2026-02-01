<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $guarded = ['id'];

    // relasi ketable category article
    public function category()
    {
        return $this->belongsTo(CategoryArticle::class, 'category_id');
    }
}
