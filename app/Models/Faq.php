<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $guarded = ['id'];

    // relasi ke table category faq
    public function category()
    {
        return $this->belongsTo(CategoryFaq::class, 'category_id');
    }
}
