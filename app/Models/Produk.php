<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'ProdukID';
    protected $fillable = ['NamaProduk', 'KategoriID', 'Harga', 'Stok', 'Gambar'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'KategoriID', 'KategoriID');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'ProdukID', 'ProdukID');
    }
}
