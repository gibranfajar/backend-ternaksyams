<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = ['id'];

    // relasi ke table variants
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
