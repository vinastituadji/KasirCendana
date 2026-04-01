<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'PenjualanID';
    protected $fillable = [
        'TanggalPenjualan', 'TotalHarga', 'Diskon',
        'StatusPembayaran', 'StatusPesanan', 'PelangganID'
    ];

    protected $casts = [
        'TanggalPenjualan' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'PelangganID', 'PelangganID');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'PenjualanID', 'PenjualanID');
    }
}
