<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandVariant extends Model
{
    protected $table = 'brand_variants';
    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
