<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@ternaksyams.id',
            'password' => bcrypt('qwerty12345'),
            'role' => 'admin',
        ]);

        $this->call([
            AboutSeeder::class,
            BrandSeeder::class,
            CategoryArticleSeeder::class,
            ArticleSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
