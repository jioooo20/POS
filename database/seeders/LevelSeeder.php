<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['level_id' => 1, 'level_kode' => 'ADM', 'level_nama' => 'Administrator', 'created_at' => now()->setTimezone('Asia/Jakarta'), 'updated_at' => now()->setTimezone('Asia/Jakarta')],
            ['level_id' => 2, 'level_kode' => 'MNG', 'level_nama' => 'Manager', 'created_at' => now()->setTimezone('Asia/Jakarta'), 'updated_at' => now()->setTimezone('Asia/Jakarta')],
            ['level_id' => 3, 'level_kode' => 'STF', 'level_nama' => 'Staff/Kasir', 'created_at' => now()->setTimezone('Asia/Jakarta'), 'updated_at' => now()->setTimezone('Asia/Jakarta')],
            ['level_id' => 4, 'level_kode' => 'SUP', 'level_nama' => 'Supplier', 'created_at' => now()->setTimezone('Asia/Jakarta'), 'updated_at' => now()->setTimezone('Asia/Jakarta')],
            ['level_id' => 5, 'level_kode' => 'PLG', 'level_nama' => 'Pelanggan', 'created_at' => now()->setTimezone('Asia/Jakarta'), 'updated_at' => now()->setTimezone('Asia/Jakarta')]
        ];

        DB::table('m_level')->insert($data);
    }
}
