<?php

namespace Database\Seeders;

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
        // Data pemilik mebel
        DB::table('users')->insert([
            'nama_pengguna' => 'Pemilik Mebel Utama',
            'email' => 'pemilik@mebel.com',
            'password' => Hash::make('password123'),
            'role' => 'pemilikmebel',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data contoh karyawan 1
        DB::table('users')->insert([
            'nama_pengguna' => 'Karyawan Pertama',
            'email' => 'karyawan1@mebel.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data contoh karyawan 2
        DB::table('users')->insert([
            'nama_pengguna' => 'Karyawan Kedua',
            'email' => 'karyawan2@mebel.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data admin/pemilik tambahan
        DB::table('users')->insert([
            'nama_pengguna' => 'Pemilik Cabang',
            'email' => 'pemilik2@mebel.com',
            'password' => Hash::make('password123'),
            'role' => 'pemilikmebel',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}