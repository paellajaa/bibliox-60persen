<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('peminjaman', function (Blueprint $table) {
        $table->text('catatan_siswa')->nullable(); // Siswa lapor kondisi
        $table->text('catatan_admin')->nullable(); // Admin kasih alasan denda
        $table->enum('status', ['menunggu', 'dipinjam', 'proses_kembali', 'kembali', 'ditolak', 'rusak'])->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            //
        });
    }
};
