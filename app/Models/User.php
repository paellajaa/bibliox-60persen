<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'pengenal'; 
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'pengenal',
        'nama',
        'email',
        'kata_sandi', // HARUS kata_sandi
        'peran',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    // Fungsi pemetaan agar Laravel mengenali kolom custom kita
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function getAuthPasswordName()
    {
        return 'kata_sandi';
    }

    public function getAuthIdentifierName()
    {
        return 'pengenal';
    }

    public function getAuthIdentifier()
    {
        return (string) $this->{$this->getAuthIdentifierName()};
    }
}