<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama (opsional biar nggak bentrok kalau di-run berkali-kali)
        User::truncate(); 

        // 1. Buat Akun Admin
        User::create([
            'pengenal'   => 'ADMIN001', // Wajib ada karena ini Primary Key di model kamu
            'nama'       => 'Admin Bibliox',
            'email'      => 'admin@bibliox.com',
            'peran'      => 'admin',
            'kata_sandi' => Hash::make('admin123'),
        ]);

        // 2. Buat Akun Anggota Contoh Dummy
        User::create([
            'pengenal'   => '2024001', // Format pengenal anggota
            'nama'       => 'Budi Santoso',
            'email'      => 'budi@gmail.com',
            'peran'      => 'anggota',
            'kata_sandi' => Hash::make('anggota123'),
        ]);

        User::create([
            'pengenal'   => '2024002', // Format pengenal anggota
            'nama'       => 'Siti Aminah',
            'email'      => 'siti@gmail.com',
            'peran'      => 'anggota',
            'kata_sandi' => Hash::make('anggota123'),
        ]);
    }
}