<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    private const GAMBAR = 'https://jalatrang.id/assets/images/web_berita/';

    public function run(): void
    {
        $admin  = User::where('email', 'admin@desa.test')->firstOrFail();
        $editor = User::where('email', 'editor@desa.test')->firstOrFail();

        // ---- Berita bawaan proyek (dari jalatrang.id) ----
        $this->buat($admin, 'Malam Penuh Gengsi Dimulai! 16 Tim Berebut Mahkota Juara di Ajang CVC Cup 2026 Desa Jalatrang', 'Olahraga', ['cikandung', 'dusun', '2026'], [
            'gambar'       => self::GAMBAR . '1790662317-whatsapp-image-2026-09-27-at-081321.jpeg',
            // TODO: tempel isi lengkap dari halaman detail website (sekarang baru kalimat pembuka).
            'isi'          => <<<'TXT'
JalatrangNews; Himpunan Pemuda-Pemudi Dusun Cikandung yang tergabung dalam Cikandung Voli...
TXT,
            'dilihat'      => 425,
            'published_at' => '2026-09-26 08:00:00',
        ]);

        $this->buat($editor, 'Pemerintah Desa Jalatrang Kukuhkan Desa Siaga TB, Perkuat Kolaborasi Lintas Sektor Basmi Tuberkulosis', 'Pendidikan', ['desa', 'jalatrang', 'siaga'], [
            'gambar'       => self::GAMBAR . '1790660451-whatsapp-image-2026-09-28-at-102755.jpeg',
            // TODO: tempel isi lengkap dari halaman detail website (sekarang baru kalimat pembuka).
            'isi'          => <<<'TXT'
JalatrangNews; Pemerintah Desa Jalatrang, Kecamatan Cipaku, Kabupaten Ciamis, menunjukkan...
TXT,
            'dilihat'      => 173,
            'published_at' => '2026-09-28 08:00:00',
        ]);

        // ---- Berita contoh dari modul Praktikum 5 ----
        $this->buat($admin, 'Turnamen Voli Antar Dusun Resmi Dibuka', 'Olahraga', ['desa', 'kegiatan'], [
            'isi'          => 'Turnamen voli tahunan antar dusun resmi dibuka oleh kepala desa...',
            'published_at' => '2026-10-03 09:00:00',
        ]);

        $this->buat($editor, 'Sosialisasi Bank Sampah untuk Warga', 'Pendidikan', ['warga', 'lingkungan'], [
            'isi'          => 'Pemerintah desa mengadakan sosialisasi pengelolaan sampah...',
            'published_at' => '2026-10-12 09:00:00',
        ]);

        $this->buat($editor, 'Panen Raya Kelompok Wanita Tani', 'potensi', ['desa', 'ekonomi'], [
            'isi'          => 'Kelompok wanita tani desa berhasil panen sayuran organik...',
            'published_at' => '2026-10-20 09:00:00',
        ]);
    }

    /**
     * @param  string[]  $tags  nama tag
     * @param  array<string, mixed>  $atribut  isi, gambar, dilihat, published_at
     */
    private function buat(User $penulis, string $judul, string $kategori, array $tags, array $atribut): void
    {
        // Dibuat lewat relasi user: user_id terisi otomatis.
        // updateOrCreate -> aman dijalankan berulang (tidak membuat data ganda).
        $berita = $penulis->beritas()->updateOrCreate(
            ['judul' => $judul],
            ['kategori_id' => Kategori::where('nama', $kategori)->firstOrFail()->id] + $atribut,
        );

        // Menempelkan tag lewat tabel pivot (many to many). sync() juga aman diulang.
        $berita->tags()->sync(Tag::whereIn('nama', $tags)->pluck('id'));
    }
}
