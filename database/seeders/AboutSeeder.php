<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About;
use App\Models\PartnerSection;
use App\Models\WhyUsFeature;
use App\Models\ProfileSection;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Buat record utama di tabel abouts
        $about = About::create([
            'banner' => 'https://via.placeholder.com/1200x400?text=About+Banner',
            'image1' => 'https://via.placeholder.com/400x300?text=Image+1',
            'image2' => 'https://via.placeholder.com/400x300?text=Image+2',
            'image3' => 'https://via.placeholder.com/400x300?text=Image+3',
            'image4' => 'https://via.placeholder.com/400x300?text=Image+4',
            'hero_title' => 'Ternak Syams Etawa Goat Milk',
            'hero_subtitle' => 'Kekuatan disetiap tetes Etawa Goat Milk',
            'hero_image' => 'https://via.placeholder.com/300x400?text=Product+Stack',
            'tagline' => 'Susu kambing Etawa bermutrisi tinggi dengan rasa lezat, rendah gula, dan berprotein tinggi. Baik untuk menjaga pemulihan stamina dari radang serta aman dan disukai anak-anak!',
            'why_us_title' => 'Kenapa Ternak Syams?',
            'achievement_count' => '300.000 +',
            'achievement_label' => 'Pelanggan puas',
        ]);

        // 2️⃣ Buat partner section
        PartnerSection::create([
            'about_id' => $about->id,
            'title' => 'Kerjasama Peternak Lokal dengan kualitas terbaik',
            'description' => 'Kami mendukung peternak lokal dengan mengambil bahan baku langsung dari mereka...',
            'image_url' => 'https://via.placeholder.com/600x400?text=Local+Partner+Image',
        ]);

        // 3️⃣ Buat why us features
        $features = [
            'Menggunakan bahan alami dengan proses yang ramah lingkungan.',
            'Produk bebas bahan kimia sehingga hasil yang didapat alami dan murni.',
            'Produk Ternak Syams tidak ada berbau prengus (kambing).',
            'Susu Etawa kami kaya vitamin dan nutrisi yang mudah diserap.',
            'Semua produk dapat dikonsumsi mulai dari anak minimal 2 tahun.',
            'Semua produk sudah tersertifikasi BPOM.',
        ];

        foreach ($features as $text) {
            WhyUsFeature::create([
                'about_id' => $about->id,
                'text' => $text,
            ]);
        }

        // 4️⃣ Buat profile section
        ProfileSection::create([
            'about_id' => $about->id,
            'founding_year' => 2020,
            'mission' => 'Sebuah brand yang didirikan oleh anak bangsa pada tahun 2020. Ternak Syams bekerjasama dengan para peternak lokal...',
            'image_embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        ]);
    }
}
