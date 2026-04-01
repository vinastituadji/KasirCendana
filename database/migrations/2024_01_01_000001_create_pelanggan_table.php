<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->increments('PelangganID');
            $table->string('NamaPelanggan', 255);
            $table->text('Alamat')->nullable();
            $table->string('NomorTelepon', 15)->nullable();
            $table->string('Email', 255)->unique();
            $table->string('Password', 255);
            $table->enum('Role', ['kasir', 'pelanggan'])->default('pelanggan');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
