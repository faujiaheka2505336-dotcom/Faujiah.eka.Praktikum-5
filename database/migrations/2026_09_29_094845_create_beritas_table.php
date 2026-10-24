<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori')->index();          // index: dipakai untuk filter
            $table->string('gambar')->nullable();         // path relatif di disk "public"
            $table->text('isi');
            $table->string('tags')->nullable();           // disimpan "voli,bola,turnamen"
            $table->unsignedInteger('dilihat')->default(0);
            $table->string('penulis')->default('Admin');
            $table->timestamp('published_at')->nullable()->index(); // tanggal terbit berita
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};