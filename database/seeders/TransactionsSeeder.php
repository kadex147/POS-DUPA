<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionsSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti 'transactions' dengan nama tabel Anda yang sebenarnya (misal: 'invoices' atau 'sales')
        $tableName = 'transactions'; 

        // Nonaktifkan foreign key checks untuk menghindari error saat truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat beberapa data dummy dengan tanggal spesifik (lawas/custom)
        $data = [
            [
                // Contoh: Transaksi tanggal 15 Januari 2023
                'invoice_number' => 'INV-20250615-0001', 
                'user_id'        => 1,                             
                'total'          => 220000.00,                     
                // Format: Carbon::create(tahun, bulan, hari, jam, menit, detik)
                'created_at'     => Carbon::create(2025, 6, 15, 10, 30, 0), 
                'updated_at'     => Carbon::create(2025, 6, 15, 10, 30, 0),
            ],

        ];

        // Insert data ke tabel
        DB::table($tableName)->insert($data);
    }
}