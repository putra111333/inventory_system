# Stokita — Sistem Inventory

Aplikasi inventory berbasis Laravel 12 untuk katalog produk, pencatatan stok masuk/keluar, laporan transaksi, serta barcode dan QR code yang dapat diunduh untuk setiap SKU. Antarmuka menggunakan Bahasa Indonesia dan database SQLite secara default.

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer
- Ekstensi PHP SQLite (`pdo_sqlite` dan `sqlite3`)

## Menjalankan aplikasi

```bash
composer install
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000` lalu masuk dengan salah satu akun demo berikut:

| Peran | Email | Kata sandi |
|---|---|---|
| Admin | `admin@inventory.test` | `password` |
| Gudang | `gudang@inventory.test` | `password` |
| Kasir | `kasir@inventory.test` | `password` |

Ganti kata sandi akun demo sebelum aplikasi digunakan di lingkungan nyata.

## Hak akses

- **Admin:** melihat dashboard dan laporan, mengelola produk (tambah, ubah, hapus), serta mencatat stok masuk/keluar.
- **Gudang dan kasir:** melihat produk dan dashboard/laporan, serta mencatat stok masuk/keluar. Pengelolaan katalog hanya tersedia bagi admin.
- **Pendaftaran akun:** hanya admin yang dapat membuka menu **Daftarkan akun** dan membuat akun baru dengan peran admin, gudang, atau kasir. Pendaftaran publik dinonaktifkan.
- **Barcode / QR code:** tersedia pada halaman produk untuk semua pengguna. Keduanya berisi SKU; barcode menggunakan Code 128, dan masing-masing dapat diunduh sebagai SVG untuk dicetak atau dipindai.

Penghapusan produk dengan riwayat stok ditolak agar jejak transaksi tetap tersedia. Jumlah stok hanya berubah melalui transaksi; stok keluar yang melebihi stok tersedia ditolak. Transaksi menyimpan waktu, jumlah, jenis, referensi, dan petugas.

## Tes

```bash
php artisan test
```

Tes menggunakan SQLite in-memory dan tidak mengubah database aplikasi.

Seeder membuat enam produk contoh dengan transaksi stok masuk dan keluar. Untuk mengisi data pada database yang sudah dibuat, jalankan `php artisan db:seed`; menjalankannya kembali tidak menggandakan produk contoh atau transaksi.
