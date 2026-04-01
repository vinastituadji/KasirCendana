<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->increments('KategoriID');
            $table->string('NamaKategori', 255);
            $table->timestamps();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->increments('ProdukID');
            $table->string('NamaProduk', 255);
            $table->unsignedInteger('KategoriID');
            $table->decimal('Harga', 10, 2);
            $table->integer('Stok')->default(0);
            $table->string('Gambar', 255)->nullable();
            $table->timestamps();

            $table->foreign('KategoriID')->references('KategoriID')->on('kategori')->onDelete('restrict');
        });

        Schema::create('penjualan', function (Blueprint $table) {
            $table->increments('PenjualanID');
            $table->date('TanggalPenjualan');
            $table->decimal('TotalHarga', 10, 2)->default(0);
            $table->decimal('Diskon', 10, 2)->default(0);
            $table->enum('StatusPembayaran', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->enum('StatusPesanan', ['aktif', 'dibatalkan', 'selesai'])->default('aktif');
            $table->unsignedInteger('PelangganID');
            $table->timestamps();

            $table->foreign('PelangganID')->references('PelangganID')->on('pelanggan')->onDelete('cascade');
        });

        Schema::create('detailpenjualan', function (Blueprint $table) {
            $table->increments('DetailID');
            $table->unsignedInteger('PenjualanID');
            $table->unsignedInteger('ProdukID');
            $table->integer('JumlahProduk');
            $table->decimal('Subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('PenjualanID')->references('PenjualanID')->on('penjualan')->onDelete('cascade');
            $table->foreign('ProdukID')->references('ProdukID')->on('produk')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detailpenjualan');
        Schema::dropIfExists('penjualan');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori');
    }
};
