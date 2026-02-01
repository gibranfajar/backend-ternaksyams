<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $guarded = ['id'];

    public function variantSize()
    {
        return $this->belongsTo(VariantSize::class, 'variantsize_id');
    }
}
