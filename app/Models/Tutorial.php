<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    protected $table = 'tutorials';

    protected $guarded = ['id'];

    // relasi ke table category
    public function category()
    {
        return $this->belongsTo(CategoryTutorial::class, 'category_id');
    }
}
