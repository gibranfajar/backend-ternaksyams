<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = ['id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function voucherUsage()
    {
        return $this->hasOne(VoucherUsage::class); // atau hasMany kalau bisa lebih dari 1
    }

    public function voucher()
    {
        return $this->hasOneThrough(
            Voucher::class,
            VoucherUsage::class,
            'order_id',  // FK di voucher_usages
            'id',        // PK di vouchers
            'id',        // PK di orders
            'voucher_id' // FK di voucher_usages
        );
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
