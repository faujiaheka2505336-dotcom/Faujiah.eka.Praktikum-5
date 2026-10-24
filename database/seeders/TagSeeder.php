<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    private const DAFTAR = [
        'desa', 'warga', 'kegiatan', 'ekonomi', 'lingkungan',
        // tag yang sebelumnya tersimpan sebagai teks pada berita bawaan proyek
        'cikandung', 'dusun', '2026', 'jalatrang', 'siaga',
    ];

    public function run(): void
    {
        foreach (self::DAFTAR as $nama) {
            Tag::firstOrCreate(['nama' => $nama]);
        }
    }
}
