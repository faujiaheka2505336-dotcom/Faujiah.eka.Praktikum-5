<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('editor')->after('email');
        });

        // Penulis tidak lagi teks bebas, tetapi relasi ke akun yang login.
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('penulis');
            $table->foreignId('user_id')->after('kategori_id')
                  ->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->string('penulis')->default('Admin');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
