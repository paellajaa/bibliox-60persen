<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;


    protected $table = 'peminjaman';

    
    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'durasi_hari',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'total_denda',
        'status_denda',
        'catatan_siswa', 
        'catatan_admin'  
    ];

    /**
     * Relasi ke model Buku
     * (Mencari data buku berdasarkan 'buku_id' di tabel peminjaman yang cocok dengan 'kode_buku' di tabel buku)
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'kode_buku');
    }

    /**
     * Relasi ke model User
     * (Mencari data user berdasarkan 'user_id' di tabel peminjaman yang cocok dengan 'pengenal' di tabel pengguna)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'pengenal');
    }
}