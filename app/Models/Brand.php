<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';
    protected $guarded = [];

    public function sizes()
    {
        return $this->hasMany(BrandSize::class);
    }

    public function variants()
    {
        return $this->hasMany(BrandVariant::class);
    }

    public function about()
    {
        return $this->hasOne(BrandAbout::class);
    }

    public function detail()
    {
        return $this->hasOne(BrandDetail::class);
    }

    public function sliders()
    {
        return $this->hasMany(BrandSlider::class);
    }

    public function feature()
    {
        return $this->hasOne(BrandFeature::class);
    }

    public function howitwork()
    {
        return $this->hasOne(BrandHowitwork::class);
    }

    public function productsidebar()
    {
        return $this->hasOne(BrandProductsidebar::class);
    }

    public function testimonial()
    {
        return $this->hasOne(BrandTestimonial::class);
    }

    public function testimonialBrands()
    {
        return $this->hasMany(TestimonialBrand::class);
    }
}
