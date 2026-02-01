<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerSection extends Model
{
    protected $table = 'partner_sections';
    protected $guarded = [];

    public function about()
    {
        return $this->belongsTo(About::class);
    }
}
