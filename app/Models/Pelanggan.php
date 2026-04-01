<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Authenticatable
{
    use Notifiable;

    protected $table = 'pelanggan';
    protected $primaryKey = 'PelangganID';

    protected $fillable = [
        'NamaPelanggan',
        'Alamat',
        'NomorTelepon',
        'Email',
        'Password',
        'Role',
    ];

    protected $hidden = ['Password', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getAuthIdentifierName()
    {
        return 'PelangganID';
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'PelangganID', 'PelangganID');
    }

    public function isKasir(): bool
    {
        return $this->Role === 'kasir';
    }

    public function isPelanggan(): bool
    {
        return $this->Role === 'pelanggan';
    }
}
