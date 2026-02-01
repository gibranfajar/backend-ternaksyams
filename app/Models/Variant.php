<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $table = 'variants';

    protected $guarded = ['id'];

    // relasi ke table product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // relasi ke table flavour
    public function flavour()
    {
        return $this->belongsTo(Flavour::class);
    }

    // relasi ke table category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // relasi ke table size
    public function sizes()
    {
        return $this->hasMany(VariantSize::class);
    }

    // relasi ke table images
    public function images()
    {
        return $this->hasMany(VariantImage::class);
    }

    public function flashSaleItems()
    {
        return $this->hasMany(FlashsaleItem::class, 'variant_id');
    }
}
