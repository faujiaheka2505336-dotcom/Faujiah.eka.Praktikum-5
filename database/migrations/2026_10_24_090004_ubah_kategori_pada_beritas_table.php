<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            // MySQL otomatis ikut menghapus index "kategori" saat kolomnya di-drop.
            $table->dropColumn('kategori');

            $table->foreignId('kategori_id')->after('judul')
                  ->constrained('kategoris');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');

            // Default dipasang agar rollback tidak gagal bila tabel sudah berisi baris;
            // index dikembalikan supaya skema sama persis dengan migration awal.
            $table->string('kategori')->default('tidak memiliki kategori')
                  ->index()->after('judul');
        });
    }
};
