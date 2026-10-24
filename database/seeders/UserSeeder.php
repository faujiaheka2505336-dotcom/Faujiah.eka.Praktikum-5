<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Password otomatis di-hash oleh cast 'password' => 'hashed' pada model User.
        // Akun ini hanya untuk latihan; jangan dipakai di server sungguhan.
        User::updateOrCreate(
            ['email' => 'admin@desa.test'],
            ['name' => 'Admin Desa', 'password' => 'password', 'role' => 'admin'],
        );

        User::updateOrCreate(
            ['email' => 'editor@desa.test'],
            ['name' => 'Editor Desa', 'password' => 'password', 'role' => 'editor'],
        );
    }
}
