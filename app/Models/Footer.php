<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $table = 'footers';
    protected $guarded = [];

    public function etawas()
    {
        return $this->hasMany(FooterEtawa::class);
    }

    public function informations()
    {
        return $this->hasMany(FooterInformation::class);
    }
}
