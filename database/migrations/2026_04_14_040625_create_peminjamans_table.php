<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('peminjaman', function (Blueprint $col) {
        $col->id();
        // UBAH DUA BARIS INI MENJADI STRING
        $col->string('user_id'); 
        $col->string('buku_id'); 
        
        $col->dateTime('tanggal_pinjam');
        $col->integer('durasi_hari');
        $col->dateTime('tanggal_jatuh_tempo');
        $col->dateTime('tanggal_kembali')->nullable();
        $col->enum('status', ['menunggu', 'dipinjam', 'kembali', 'ditolak'])->default('menunggu');
        $col->integer('denda')->default(0);
        $col->timestamps();
    });
}

    public function down(): void {
        Schema::dropIfExists('peminjaman');
    }
};