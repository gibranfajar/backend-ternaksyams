<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashsaleItem extends Model
{
    protected $table = 'flashsale_items';

    protected $guarded = ['id'];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'flashsale_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function variantSize()
    {
        return $this->belongsTo(VariantSize::class, 'variantsize_id');
    }
}
