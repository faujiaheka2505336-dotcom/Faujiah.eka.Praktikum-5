<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom string "tags" digantikan relasi many-to-many (tabel pivot berita_tag).
        // Kolom ini harus dihapus karena atribut model selalu menang atas relasi
        // dengan nama yang sama, sehingga $berita->tags mengembalikan null.
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }

    public function down(): void
    {
        // Kembalikan seperti definisi awal supaya rollback tetap konsisten.
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('tags')->nullable()->after('isi');
        });
    }
};