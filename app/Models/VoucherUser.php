<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUser extends Model
{
    protected $table = 'voucher_users';

    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
