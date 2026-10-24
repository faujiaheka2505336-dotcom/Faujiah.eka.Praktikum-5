<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan penting: user, kategori, dan tag harus ada sebelum berita
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            TagSeeder::class,
            BeritaSeeder::class,
        ]);
    }
}
