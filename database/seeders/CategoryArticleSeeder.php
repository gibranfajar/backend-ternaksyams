<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\CategoryArticle;

class CategoryArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Kesehatan',
            'Anak-anak',
            'Nutrisi',
            'Ibu Hamil',
            'Tips Harian',
        ];

        foreach ($categories as $name) {
            CategoryArticle::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        echo "Category Articles berhasil dibuat!\n";
    }
}
