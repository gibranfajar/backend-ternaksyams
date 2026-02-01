<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\CategoryArticle;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $kesehatan = CategoryArticle::where('slug', 'kesehatan')->first()->id ?? 1;
        $anakAnak  = CategoryArticle::where('slug', 'anak-anak')->first()->id ?? 1;
        $nutrisi   = CategoryArticle::where('slug', 'nutrisi')->first()->id ?? 1;

        // === 1️⃣ Artikel Kesehatan ===
        Article::create([
            'title' => 'Manfaat Susu Kambing GOATA untuk Kesehatan',
            'slug' => Str::slug('Manfaat Susu Kambing GOATA untuk Kesehatan'),
            'excerpt' => 'Susu kambing GOATA dikenal memiliki banyak manfaat bagi kesehatan tubuh, mulai dari meningkatkan imunitas hingga memperbaiki kualitas pencernaan.',
            'content' => '
                <p>Susu kambing GOATA merupakan salah satu pilihan terbaik bagi Anda yang ingin meningkatkan kesehatan tubuh secara alami. Dibuat dari peternakan TernakSyams yang menjaga standar kebersihan dan kualitas, produk ini memberikan manfaat nutrisi yang lebih tinggi dibandingkan susu kambing biasa.</p>

                <p>Salah satu manfaat utamanya adalah kemampuannya membantu pencernaan. Susu kambing memiliki struktur lemak lebih kecil dan lebih mudah dicerna dibandingkan susu sapi. Hal ini membuatnya cocok bagi orang yang sering mengalami kembung atau intoleransi ringan terhadap laktosa.</p>

                <p>Kandungan kalsium dan mineral dalam susu kambing GOATA juga berfungsi untuk memperkuat tulang, gigi, dan mendukung metabolisme tubuh. Ini sangat membantu terutama untuk orang dewasa, lansia, atau wanita yang membutuhkan dukungan kesehatan tulang.</p>

                <p>Selain itu, susu kambing kaya akan probiotik alami yang membantu menyehatkan usus. Probiotik ini berperan besar dalam menjaga daya tahan tubuh, sehingga tubuh menjadi lebih kuat melawan infeksi dan penyakit harian.</p>

                <p>Konsumsi rutin susu kambing GOATA tidak hanya memberikan manfaat kesehatan, tetapi juga membantu menjaga energi tubuh agar tetap stabil sepanjang hari. Rasanya yang lembut dan tidak eneg membuatnya bisa dikonsumsi siapa saja, termasuk anak-anak.</p>
            ',
            'thumbnail' => 'articles/dummy1.jpg',
            'category_id' => $kesehatan,
        ]);

        // === 2️⃣ Artikel Anak-anak ===
        Article::create([
            'title' => 'Kenapa Susu Kambing Cocok untuk Anak-anak?',
            'slug' => Str::slug('Kenapa Susu Kambing Cocok untuk Anak-anak?'),
            'excerpt' => 'Banyak penelitian menunjukkan bahwa susu kambing lebih mudah dicerna oleh anak-anak, sehingga cocok sebagai pilihan minuman harian mereka.',
            'content' => '
                <p>Bagi anak-anak yang sedang dalam masa pertumbuhan, kebutuhan nutrisi mereka harus terpenuhi dengan baik. Susu kambing menjadi salah satu opsi terbaik karena memiliki struktur protein yang lebih ringan dan mudah dicerna.</p>

                <p>Susu kambing juga mengandung vitamin A dan B yang mendukung perkembangan otak dan sistem kekebalan tubuh anak. Kandungan mineralnya membantu memperkuat tulang dan gigi tanpa menimbulkan risiko alergi yang cukup tinggi seperti pada susu sapi.</p>

                <p>Banyak orang tua memilih susu kambing GOATA karena rasanya yang lembut dan tidak menyebabkan perut kembung. Anak-anak yang sebelumnya tidak cocok dengan susu sapi seringkali merasa lebih nyaman mengonsumsi susu kambing.</p>

                <p>Tidak hanya itu, susu kambing juga kaya akan lemak baik yang membantu perkembangan sel dan jaringan tubuh pada anak. Ini sangat penting terutama pada anak usia aktif yang membutuhkan energi tambahan.</p>

                <p>Dengan proses produksi yang higienis dan terstandarisasi, GOATA memastikan anak-anak mendapatkan manfaat nutrisi terbaik tanpa tambahan zat kimia yang tidak diperlukan.</p>
            ',
            'thumbnail' => 'articles/dummy2.jpg',
            'category_id' => $anakAnak,
        ]);

        // === 3️⃣ Artikel Nutrisi ===
        Article::create([
            'title' => 'Nutrisi Penting dalam Susu Kambing GOATA',
            'slug' => Str::slug('Nutrisi Penting dalam Susu Kambing GOATA'),
            'excerpt' => 'Susu kambing GOATA kaya akan nutrisi penting yang membantu kesehatan tubuh, seperti protein berkualitas tinggi, kalsium, serta probiotik alami.',
            'content' => '
                <p>Susu kambing GOATA memiliki kandungan nutrisi yang membuatnya menjadi salah satu minuman paling sehat untuk dikonsumsi setiap hari. Salah satu komponen terpenting dalam susu kambing adalah proteinnya yang mudah dicerna oleh tubuh.</p>

                <p>Kandungan kalsium dalam susu kambing membantu menjaga kesehatan tulang dan mencegah risiko osteoporosis pada usia dewasa. Selain itu, susu kambing juga mengandung fosfor dan magnesium yang bekerja bersama kalsium dalam memperkuat struktur tulang.</p>

                <p>Yang membuat GOATA lebih unggul adalah adanya probiotik alami. Probiotik berperan sebagai penjaga kesehatan usus dan meningkatkan imunitas tubuh. Dengan usus yang sehat, tubuh dapat menyerap nutrisi lebih baik sehingga metabolisme meningkat.</p>

                <p>Susu kambing juga kaya vitamin A yang penting untuk kesehatan mata dan kulit. Vitamin ini membantu menjaga kelembapan kulit dan mendukung regenerasi sel, membuat tubuh tampak lebih segar.</p>

                <p>Selain itu, lemak baik dalam susu kambing GOATA berfungsi sebagai sumber energi stabil tanpa membuat perut terasa berat. Ini cocok untuk dikonsumsi oleh anak-anak, orang dewasa aktif, maupun lansia.</p>
            ',
            'thumbnail' => 'articles/dummy3.jpg',
            'category_id' => $nutrisi,
        ]);

        echo "Articles dengan konten panjang berhasil dibuat!\n";
    }
}
