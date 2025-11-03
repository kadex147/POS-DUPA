<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus atau beri komentar pada factory jika tidak dipakai
        // User::factory(10)->create();

        // Cara ini lebih jelas dan tidak bergantung pada factory
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',              // ← TAMBAH INI
            'role' => 'admin',                      // ← TAMBAH INI
            'status' => 'aktif',                    // ← TAMBAH INI
        ]);
    }
}