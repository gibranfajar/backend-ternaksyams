<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSection extends Model
{
    protected $table = 'profile_sections';
    protected $guarded = [];

    public function about()
    {
        return $this->belongsTo(About::class);
    }
}
