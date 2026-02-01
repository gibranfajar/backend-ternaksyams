<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{
    Brand,
    BrandSize,
    BrandVariant,
    BrandDetail,
    BrandTestimonial,
    BrandFeature,
    BrandProductsidebar,
    BrandAbout,
    BrandHowitwork
};

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        // === 1️⃣ Brand Utama ===
        $brand = Brand::create([
            'brand' => 'GOATA',
            'slug' => Str::slug('GOATA'),
            'description' => 'Susu kambing murni produksi TernakSyams dengan kualitas premium dan nutrisi lengkap.',
            'image' => 'brands/goata-brand.jpg', // ganti sesuai uploadmu
            'main_color' => '#FFD700',
            'accent_color' => '#000000'
        ]);

        // === 2️⃣ Brand Sizes ===
        $sizes = ['200 gram', '500 gram', '1000 gram'];
        foreach ($sizes as $size) {
            BrandSize::create([
                'brand_id' => $brand->id,
                'size' => $size,
            ]);
        }

        // === 3️⃣ Brand Variants ===
        $variants = [
            [
                'name' => 'Original',
                'description' => 'Varian original tanpa tambahan rasa, murni dari susu kambing.',
                'image' => 'brand_variants/variant-original.jpg',
            ],
            [
                'name' => 'Kolostrum',
                'description' => 'Varian coklat dengan rasa manis alami dari kolostrum.',
                'image' => 'brand_variants/variant-choco.jpg',
            ],
            [
                'name' => 'Fat Faster',
                'description' => 'Varian strawberry dengan tambahan vitamin untuk energi ekstra.',
                'image' => 'brand_variants/variant-strawberry.jpg',
            ],
        ];

        foreach ($variants as $v) {
            BrandVariant::create([
                'brand_id' => $brand->id,
                'variant' => $v['name'],
                'description' => $v['description'],
                'image' => $v['image'],
            ]);
        }

        // === 4️⃣ Brand Detail ===
        BrandDetail::create([
            'brand_id' => $brand->id,
            'herotitle' => 'Susu Kambing Premium GOATA',
            'herosubtitle' => 'Dari peternakan sehat TernakSyams langsung ke rumah Anda.',
            'banner' => 'brand_banners/goata-banner.jpg',
        ]);

        // === 5️⃣ Testimonials ===
        BrandTestimonial::create([
            'brand_id' => $brand->id,
            'quotes' => 'Saya minum GOATA setiap pagi, badan lebih segar.',
            'textreview' => 'Rasa enak, tidak eneg, dan manfaatnya terasa dalam beberapa minggu.',
            'textcta' => 'Baca Testimonial Lengkap',
            'linkcta' => 'https://ternaksyams.com/goata-testimonials',
            'cardcolor' => '#FFFFFF',
            'textcolor' => '#000000',
        ]);

        // === 6️⃣ Features (Marquee) ===
        BrandFeature::create([
            'brand_id' => $brand->id,
            'marquebgcolor' => '#fef8e7',
            'marquetextcolor' => '#4a3a2f',
            'features' => '100% NATURAL • TANPA PENGAWET • KAYA NUTRISI • PRODUK TERNAKSYAMS',
        ]);

        // === 7️⃣ Product Sidebar ===
        BrandProductsidebar::create([
            'brand_id' => $brand->id,
            'headline' => 'Kenapa Harus GOATA?',
            'description' => 'Produk susu kambing terbaik untuk seluruh anggota keluarga.',
            'ctatext' => 'Lihat Produk',
            'ctalink' => 'https://ternaksyams.com/goata',
            'cardcolor' => '#E6F3FF',
            'textcolor' => '#000000',
        ]);

        // === 8️⃣ About Section ===
        BrandAbout::create([
            'brand_id' => $brand->id,
            'title' => 'Tentang GOATA',
            'description' => 'GOATA adalah susu kambing murni yang diproduksi oleh TernakSyams dengan standar kebersihan tinggi.',
            'image' => 'brand_about/goata-about.jpg',
            'ctatext' => 'Pelajari Lebih Lanjut',
            'ctalink' => 'https://ternaksyams.com/about-goata',
        ]);

        // === 9️⃣ How It Works ===
        BrandHowitwork::create([
            'brand_id' => $brand->id,
            'tagline' => 'Proses Produksi GOATA',
            'image' => 'brand_howitwork/goata-process.jpg',
            'headline' => 'Dari Peternak ke Gelas Anda',
            'steps' => "1. Pemerahan\n2. Pasteurisasi\n3. Pengemasan higienis\n4. Pengiriman",
            'ctatext' => 'Lihat Produk',
            'ctalink' => 'https://ternaksyams.com/goata',
        ]);

        echo "Brand GOATA berhasil dibuat!\n";
    }
}
