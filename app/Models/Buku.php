<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'kode_buku'; 

    protected $fillable = [
        'judul', 
        'penulis', 
        'kategori', 
        'stok', 
        'tahun_terbit',
        'cover' 
    ];
}