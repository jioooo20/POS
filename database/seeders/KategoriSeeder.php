<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_kode' => 'ELKTRN',
                'kategori_nama' => 'Elektronik',
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta'),
            ],
            [
                'kategori_kode' => 'PAKNN',
                'kategori_nama' => 'Pakaian',
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta'),
            ],
            [
                'kategori_kode' => 'MKNAN',
                'kategori_nama' => 'Makanan',
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta'),
            ],
            [
                'kategori_kode' => 'MNMAN',
                'kategori_nama' => 'Minuman',
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta'),
            ],
            [
                'kategori_kode' => 'ALTSKR',
                'kategori_nama' => 'Alat Sekolah',
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta'),
            ],
        ];
        DB::table('m_kategori')->insert($data);
    }
}
