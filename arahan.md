## Akun Demo

| Role      | Email                  | Password |
| --------- | ---------------------- | -------- |
| Kasir     | kasir@kasircendana.com | password |
| Pelanggan | budi@email.com         | password |
| Pelanggan | siti@email.com         | password |

---

## Struktur Folder Project

```
KasirCendana/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── Kasir/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProdukController.php
│   │   │   │   ├── KategoriController.php
│   │   │   │   ├── PelangganController.php
│   │   │   │   ├── TransaksiController.php
│   │   │   │   └── LaporanController.php
│   │   │   └── Pelanggan/
│   │   │       ├── KatalogController.php
│   │   │       ├── PesananController.php
│   │   │       └── ProfilController.php
│   │   └── Middleware/
│   │       ├── KasirMiddleware.php
│   │       ├── PelangganMiddleware.php
│   │       └── GuestPelangganMiddleware.php
│   └── Models/
│       ├── Pelanggan.php
│       ├── Kategori.php
│       ├── Produk.php
│       ├── Penjualan.php
│       └── DetailPenjualan.php
├── bootstrap/
│   └── app.php
├── config/
│   └── auth.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_pelanggan_table.php
│   │   └── 2024_01_01_000002_create_main_tables.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   ├── css/app.css
│   ├── js/app.js
│   └── images/products/
├── resources/views/
│   ├── layouts/
│   │   ├── kasir.blade.php
│   │   └── public.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── kasir/
│   │   ├── dashboard/index.blade.php
│   │   ├── produk/{index,create,edit}.blade.php
│   │   ├── kategori/index.blade.php
│   │   ├── pelanggan/{index,show}.blade.php
│   │   ├── transaksi/{index,show}.blade.php
│   │   └── laporan/index.blade.php
│   └── pelanggan/
│       ├── katalog/index.blade.php
│       ├── pesanan/index.blade.php
│       └── profil/index.blade.php
└── routes/
    └── web.php
```

---

## Langkah Instalasi di Laragon

### 1. Persiapan Laragon

- Unduh dan install **Laragon Full** dari https://laragon.org
- Pastikan **Apache**, **MySQL**, dan **PHP 8.1+** sudah berjalan (ikon hijau di Laragon)

### 2. Buat Project Laravel Baru

Buka terminal Laragon (klik kanan tray → Terminal) dan jalankan:

```bash
cd C:\laragon\www
composer create-project laravel/laravel KasirCendana
cd KasirCendana
```

### 3. Konfigurasi .env

Buka file `.env` di root project, ubah bagian database:

```env
APP_NAME=KasirCendana
APP_URL=http://kasircendana.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kasircendana
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database di phpMyAdmin

- Buka browser: http://localhost/phpmyadmin
- Klik **"New"** di sidebar kiri
- Beri nama database: `kasircendana`
- Pilih collation: `utf8mb4_unicode_ci`
- Klik **"Create"**

### 6. Jalankan Migration & Seeder

Di terminal Laragon, dari folder project:

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

Output yang diharapkan:

```
INFO  Running migrations.
  2024_01_01_000001_create_pelanggan_table .......... DONE
  2024_01_01_000002_create_main_tables .............. DONE
INFO  Seeding database.
```

### 7. Buat Folder Upload Gambar

```bash
mkdir public\images\products
```

### 8. Tambah Virtual Host di Laragon (Opsional)

Laragon otomatis membaca folder di `C:\laragon\www` sebagai virtual host.
Akses project via: **http://kasircendana.test**

Atau akses langsung: **http://localhost/KasirCendana/public**

---

````


## SQL Sample Data (Alternatif Manual)

Jika `php artisan db:seed` gagal, import SQL berikut di phpMyAdmin:

```sql
-- Jalankan di database kasircendana

INSERT INTO `pelanggan` (`NamaPelanggan`,`Alamat`,`NomorTelepon`,`Email`,`Password`,`Role`,`created_at`,`updated_at`) VALUES
('Admin Kasir','Jl. Cendana No. 1','08123456789','kasir@kasircendana.com','$2y$12$YourHashedPasswordHere','kasir',NOW(),NOW()),
('Budi Santoso','Jl. Merdeka No. 12','08512345678','budi@email.com','$2y$12$YourHashedPasswordHere','pelanggan',NOW(),NOW());
-- Catatan: Gunakan php artisan db:seed untuk hash password yang benar.

INSERT INTO `kategori` (`NamaKategori`,`created_at`,`updated_at`) VALUES
('Kursi',NOW(),NOW()),('Sofa',NOW(),NOW()),('Meja',NOW(),NOW()),
('Lemari',NOW(),NOW()),('Hiasan Dinding',NOW(),NOW()),
('Rak',NOW(),NOW()),('Tempat Tidur',NOW(),NOW());
````

> **Rekomendasi:** Selalu gunakan `php artisan db:seed` agar password di-hash dengan benar.

---

## Troubleshooting

| Masalah                             | Solusi                                                                      |
| ----------------------------------- | --------------------------------------------------------------------------- |
| `Class 'App\Models\User' not found` | Hapus baris `'users'` dari `config/auth.php` providers jika tidak digunakan |
| `419 Page Expired` saat login       | Jalankan `php artisan key:generate`                                         |
| Halaman kosong / 500 Error          | Cek `storage/logs/laravel.log`                                              |
| Gambar tidak tampil                 | Pastikan folder `public/images/products` ada dan writable                   |
| Font tidak muncul offline           | Ikuti langkah Font Offline di atas                                          |
| `SQLSTATE` error saat migrate       | Pastikan nama database di `.env` sudah benar                                |
| Session tidak tersimpan             | Jalankan `php artisan storage:link` dan cek permissions folder `storage/`   |

---

## Fitur Ringkasan

### Kasir (Admin)

- Dashboard dengan statistik real-time dan grafik penjualan
- CRUD Produk dengan upload gambar
- CRUD Kategori dengan jumlah produk
- Manajemen Pelanggan dengan riwayat pesanan
- Transaksi: buat pesanan, tambah banyak produk, diskon persen/nominal, tandai lunas, batalkan
- Laporan: filter tanggal, produk terlaris, pesanan masuk, riwayat, pesanan dibatalkan

### Pelanggan

- Katalog produk tanpa login
- Search dan filter kategori
- Pesan Sekarang (wajib login)
- Riwayat pesanan dengan status pembayaran
- Batalkan pesanan (hanya belum lunas)
- Edit profil dan password

### Umum

- Satu halaman login untuk semua role
- Auto-redirect berdasarkan role (kasir/pelanggan)
- FAQ floating button dengan konten sesuai role
- Responsive (desktop & mobile)
- SVG icons (tanpa emoji, tanpa CDN icon)
