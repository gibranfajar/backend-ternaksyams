<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelOrder extends Model
{
    protected $table = 'cancel_orders';
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
