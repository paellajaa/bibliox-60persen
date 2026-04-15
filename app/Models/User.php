<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Tambahkan ini
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // 2. Gunakan trait di sini

    protected $table = 'pengguna';
    protected $primaryKey = 'pengenal'; // Pastikan sesuai kolom ID unik kamu
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'pengenal',
        'nama',
        'email',
        'password',
        'peran',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}