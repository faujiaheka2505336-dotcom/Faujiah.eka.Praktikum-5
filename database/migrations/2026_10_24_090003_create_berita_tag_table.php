<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nama pivot: dua nama model huruf kecil, urut abjad, dipisah "_".
        Schema::create('berita_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('beritas')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->unique(['berita_id', 'tag_id']); // satu tag hanya sekali per berita
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_tag');
    }
};
