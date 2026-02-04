<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlashSale extends Model
{
    // use SoftDeletes;

    protected $table = 'flash_sales';

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(FlashsaleItem::class, 'flashsale_id', 'id');
    }
}
