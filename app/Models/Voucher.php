<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'voucher_users', 'voucher_id', 'user_id');
    }

    public function variantSizes()
    {
        return $this->belongsToMany(VariantSize::class, 'voucher_products', 'voucher_id', 'variantsize_id');
    }

    public function used()
    {
        return $this->hasMany(VoucherUsage::class, 'voucher_id', 'id');
    }
}
