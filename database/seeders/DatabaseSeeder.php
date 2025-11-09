<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(UserSeeder::class); // Contoh cara lama

        // Panggil UsersSeeder.php dan seeder lain Anda di dalam array ini
        $this->call([
            UsersSeeder::class,
            // Tambahkan seeder lain Anda di sini (misalnya CategorySeeder::class)
        ]);
    }
}