<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Delete all existing users
        User::truncate();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // Create Kasir user
        User::create([
            'username' => 'kasir',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('kasir123'),
            'phone' => '081234567891',
            'role' => 'kasir',
            'status' => 'aktif',
        ]);
    }
}