<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /** Kategori persis seperti dropdown di jalatrang.id/berita (urut abjad). */
    private const DAFTAR = [
        'ekonomi', 'keuangan', 'kunjungan', 'Olahraga', 'pembangunan',
        'pemuda', 'Pendidikan', 'potensi', 'Prestasi dan Apresiasi',
        'tidak memiliki kategori',
    ];

    public function run(): void
    {
        foreach (self::DAFTAR as $nama) {
            // firstOrCreate -> aman dijalankan berulang (kolom nama bersifat unique)
            Kategori::firstOrCreate(['nama' => $nama]);
        }
    }
}
