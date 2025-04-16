<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id'=> 1,
                'username' => 'admin',
                'nama' => 'Administrator',
                'password' => Hash::make('123456'),
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta')
            ],
            [
                'user_id' => 2,
                'level_id'=> 2,
                'username' => 'manager',
                'nama' => 'Manager',
                'password' => Hash::make('123456'),
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta')
            ],[
                'user_id' => 3,
                'level_id'=> 3,
                'username' => 'staff',
                'nama' => 'Staff/Kasir',
                'password' => Hash::make('123456'),
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta')
            ],
            [
                'user_id' => 4,
                'level_id'=> 4,
                'username' => 'makmur',
                'nama' => 'Makmur Suprapdi',
                'password' => Hash::make('123456'),
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta')
            ],
            [
                'user_id' => 5,
                'level_id'=> 4,
                'username' => 'jaya',
                'nama' => 'Jaya Wicaksana',
                'password' => Hash::make('123456'),
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta')
            ],
            [
                'user_id' => 6,
                'level_id'=> 4,
                'username' => 'abdi',
                'nama' => 'King Abdi',
                'password' => Hash::make('123456'),
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta')
            ],
            [
                'user_id' => 7,
                'level_id'=> 5,//pelanggan
                'username' => 'budi',
                'nama' => 'Budi Santoso',
                'password' => Hash::make('123456'),
                'created_at' => now()->setTimezone('Asia/Jakarta'),
                'updated_at' => now()->setTimezone('Asia/Jakarta')
            ]
        ];
        DB::table('m_user')->insert($data);
    }
}
