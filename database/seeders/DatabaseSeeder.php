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
        // PERBAIKAN: Panggil PenggunaSeeder milikmu, 
        // jangan pakai User::factory() bawaan Laravel
        $this->call([
            PenggunaSeeder::class,
        ]);
    }
}