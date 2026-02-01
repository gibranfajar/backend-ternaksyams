<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $table = 'abouts';
    protected $guarded = [];

    public function partnerSection()
    {
        return $this->hasOne(PartnerSection::class);
    }

    public function whyUsFeatures()
    {
        return $this->hasMany(WhyUsFeature::class);
    }

    public function profileSection()
    {
        return $this->hasOne(ProfileSection::class);
    }
}
