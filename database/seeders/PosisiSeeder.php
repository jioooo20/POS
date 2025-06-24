<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_posisi')->insert([
            ['posisi_kode' => 'WD001', 'posisi_nama' => 'Web Developer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'BD002', 'posisi_nama' => 'Backend Developer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'DE003', 'posisi_nama' => 'Database Engineer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'FD004', 'posisi_nama' => 'Frontend Developer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'FS005', 'posisi_nama' => 'Full Stack Developer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'DO006', 'posisi_nama' => 'DevOps Engineer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'MD007', 'posisi_nama' => 'Mobile Developer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'UI008', 'posisi_nama' => 'UI/UX Designer', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'SA009', 'posisi_nama' => 'Software Architect', 'created_at' => now(), 'updated_at' => now()],
            ['posisi_kode' => 'QA010', 'posisi_nama' => 'Quality Assurance Engineer', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
