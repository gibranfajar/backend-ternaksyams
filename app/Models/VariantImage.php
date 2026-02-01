<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantImage extends Model
{
    protected $table = 'variant_images';

    protected $guarded = ['id'];

    // relasi ke table variant
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
