<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Pelanggan
        DB::table('pelanggan')->insert([
            [
                'NamaPelanggan' => 'Admin Kasir',
                'Alamat' => 'Jl. Cendana No. 1, Banjarmasin',
                'NomorTelepon' => '08123456789',
                'Email' => 'kasir@kasircendana.com',
                'Password' => Hash::make('password'),
                'Role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NamaPelanggan' => 'Budi Santoso',
                'Alamat' => 'Jl. Merdeka No. 12, Banjarmasin',
                'NomorTelepon' => '08512345678',
                'Email' => 'budi@email.com',
                'Password' => Hash::make('password'),
                'Role' => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NamaPelanggan' => 'Siti Rahayu',
                'Alamat' => 'Jl. Sudirman No. 45, Banjarbaru',
                'NomorTelepon' => '08234567890',
                'Email' => 'siti@email.com',
                'Password' => Hash::make('password'),
                'Role' => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NamaPelanggan' => 'Ahmad Fauzi',
                'Alamat' => 'Jl. Ahmad Yani No. 7, Martapura',
                'NomorTelepon' => '08765432109',
                'Email' => 'ahmad@email.com',
                'Password' => Hash::make('password'),
                'Role' => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NamaPelanggan' => 'Dewi Lestari',
                'Alamat' => 'Jl. Gatot Subroto No. 23, Banjarmasin',
                'NomorTelepon' => '08998877665',
                'Email' => 'dewi@email.com',
                'Password' => Hash::make('password'),
                'Role' => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed Kategori
        DB::table('kategori')->insert([
            ['NamaKategori' => 'Kursi', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Sofa', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Meja', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Lemari', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Hiasan Dinding', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Rak', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Tempat Tidur', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed Produk
        DB::table('produk')->insert([
            ['NamaProduk' => 'Kursi Kerja Ergonomis', 'KategoriID' => 1, 'Harga' => 1250000, 'Stok' => 15, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Kursi Makan Minimalis', 'KategoriID' => 1, 'Harga' => 450000, 'Stok' => 30, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Kursi Tamu Rotan', 'KategoriID' => 1, 'Harga' => 850000, 'Stok' => 10, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Sofa 3 Dudukan Linen', 'KategoriID' => 2, 'Harga' => 4500000, 'Stok' => 5, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Sofa L-Shape Modern', 'KategoriID' => 2, 'Harga' => 7800000, 'Stok' => 3, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Sofa Minimalis 2 Seat', 'KategoriID' => 2, 'Harga' => 2900000, 'Stok' => 8, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Meja Makan Kayu Jati 6 Kursi', 'KategoriID' => 3, 'Harga' => 6500000, 'Stok' => 4, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Meja Kerja Minimalis', 'KategoriID' => 3, 'Harga' => 1800000, 'Stok' => 12, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Meja Kopi Kaca Bundar', 'KategoriID' => 3, 'Harga' => 950000, 'Stok' => 9, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Lemari Pakaian 4 Pintu', 'KategoriID' => 4, 'Harga' => 5200000, 'Stok' => 6, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Lemari Minimalis Sliding', 'KategoriID' => 4, 'Harga' => 3800000, 'Stok' => 7, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Hiasan Dinding Kayu Ukir', 'KategoriID' => 5, 'Harga' => 350000, 'Stok' => 25, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Frame Foto Set 3 Pcs', 'KategoriID' => 5, 'Harga' => 185000, 'Stok' => 40, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Cermin Dinding Oval Emas', 'KategoriID' => 5, 'Harga' => 620000, 'Stok' => 14, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Rak Buku Dinding Floating', 'KategoriID' => 6, 'Harga' => 480000, 'Stok' => 20, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Rak Sepatu 5 Tingkat', 'KategoriID' => 6, 'Harga' => 720000, 'Stok' => 18, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Tempat Tidur Queen Size', 'KategoriID' => 7, 'Harga' => 4200000, 'Stok' => 5, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
            ['NamaProduk' => 'Tempat Tidur Single Minimalis', 'KategoriID' => 7, 'Harga' => 2100000, 'Stok' => 8, 'Gambar' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed Penjualan
        DB::table('penjualan')->insert([
            ['TanggalPenjualan' => '2024-11-01', 'TotalHarga' => 5750000, 'Diskon' => 0, 'StatusPembayaran' => 'lunas', 'StatusPesanan' => 'selesai', 'PelangganID' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['TanggalPenjualan' => '2024-11-05', 'TotalHarga' => 7800000, 'Diskon' => 500000, 'StatusPembayaran' => 'lunas', 'StatusPesanan' => 'selesai', 'PelangganID' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['TanggalPenjualan' => '2024-11-10', 'TotalHarga' => 1250000, 'Diskon' => 0, 'StatusPembayaran' => 'belum_lunas', 'StatusPesanan' => 'aktif', 'PelangganID' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['TanggalPenjualan' => '2024-11-15', 'TotalHarga' => 4500000, 'Diskon' => 200000, 'StatusPembayaran' => 'lunas', 'StatusPesanan' => 'selesai', 'PelangganID' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['TanggalPenjualan' => '2024-11-20', 'TotalHarga' => 950000, 'Diskon' => 0, 'StatusPembayaran' => 'belum_lunas', 'StatusPesanan' => 'aktif', 'PelangganID' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['TanggalPenjualan' => '2024-11-22', 'TotalHarga' => 3400000, 'Diskon' => 0, 'StatusPembayaran' => 'belum_lunas', 'StatusPesanan' => 'dibatalkan', 'PelangganID' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed Detail Penjualan
        DB::table('detailpenjualan')->insert([
            ['PenjualanID' => 1, 'ProdukID' => 4, 'JumlahProduk' => 1, 'Subtotal' => 4500000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 1, 'ProdukID' => 2, 'JumlahProduk' => 2, 'Subtotal' => 900000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 1, 'ProdukID' => 13, 'JumlahProduk' => 1, 'Subtotal' => 185000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 2, 'ProdukID' => 5, 'JumlahProduk' => 1, 'Subtotal' => 7800000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 3, 'ProdukID' => 1, 'JumlahProduk' => 1, 'Subtotal' => 1250000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 4, 'ProdukID' => 4, 'JumlahProduk' => 1, 'Subtotal' => 4500000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 5, 'ProdukID' => 9, 'JumlahProduk' => 1, 'Subtotal' => 950000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 6, 'ProdukID' => 8, 'JumlahProduk' => 1, 'Subtotal' => 1800000, 'created_at' => now(), 'updated_at' => now()],
            ['PenjualanID' => 6, 'ProdukID' => 3, 'JumlahProduk' => 1, 'Subtotal' => 850000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
