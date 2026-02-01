<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shippings';
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shippingInfo()
    {
        return $this->belongsTo(ShippingInformation::class, 'shipping_information_id');
    }

    public function shippingOption()
    {
        return $this->belongsTo(ShippingOption::class, 'shipping_options_id');
    }
}
