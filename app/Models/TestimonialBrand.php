<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialBrand extends Model
{
    protected $table = 'testimonial_brands';
    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
