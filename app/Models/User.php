<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'pengenal';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nama', 'pengenal', 'email', 'kata_sandi', 'peran'];

    protected $hidden = ['kata_sandi', 'remember_token'];

    // Memberitahu Laravel nama kolom password kita adalah 'kata_sandi'
    public function getAuthPasswordName()
    {
        return 'kata_sandi';
    }

    // Mengambil nilai password untuk dicocokkan
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }
}