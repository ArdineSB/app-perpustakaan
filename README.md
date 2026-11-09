# app-perpustakaan

Sistem Perpustakaan Digital Kampus — aplikasi web Laravel 12 untuk mengelola buku, kategori, anggota, dan transaksi peminjaman, dibangun bertahap sepanjang mata kuliah Pemrograman Framework (PENS), checkpoint `[P-1]` s.d. `[P-10]` di branch `dev`.

## Fitur

- CRUD `books`, `categories`, `members`, `loans` (Eloquent + Form Request validation)
- Relasi Eloquent: `Loan` → `Member`/`User`/`LoanItem` → `Book` → `Category`, dengan eager loading
- Autentikasi session (login/logout) + middleware `auth` dan `CheckAdminRole` (role admin/petugas)
- REST API JSON (`/api/books`, `/api/members`, `/api/loans`, `/api/stats`) via API Resource
- Dashboard statistik dan Laporan Peminjaman yang mengonsumsi API internal lewat Laravel HTTP Client
- Seeder & Factory dengan data dummy bergaya Indonesia (`APP_FAKER_LOCALE=id_ID`)
- Search nama anggota, badge status peminjaman, halaman profil + ganti password

## Cara menjalankan secara lokal

Prasyarat: PHP >= 8.2, Composer, Git, dan MySQL berjalan (mis. lewat Laragon).

```bash
git clone https://github.com/ArdineSB/app-perpustakaan.git
cd app-perpustakaan
git checkout dev
composer install
copy .env.example .env
php artisan key:generate
```

Atur database di `.env` (MySQL, database `db_perpustakaan`):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpustakaan
DB_USERNAME=root
```

Buat databasenya kalau belum ada, lalu migrate + seed:

```bash
mysql -u root -e "CREATE DATABASE db_perpustakaan"
php artisan migrate:fresh --seed
```

## Dua server — WAJIB untuk Dashboard & Laporan

`php artisan serve` hanya memproses satu request dalam satu waktu, jadi kalau aplikasi memanggil API-nya sendiri lewat port yang sama akan deadlock. Karena itu jalankan **dua** server:

```bash
php artisan serve --port=8000   # server utama untuk browser
php artisan serve --port=8011    # server kedua untuk API internal
```

Server kedua dibaca lewat `INTERNAL_API_URL=http://127.0.0.1:8011` di `.env` (terdaftar di `config/services.php` sebagai `services.internal_api.base_url`). Dashboard (`GET /api/stats`) dan Laporan Peminjaman (`GET /api/loans`) memanggil server ini lewat `Http::get()`. Kalau server kedua mati, kedua halaman tetap tampil tapi kosong/angka nol (fallback `try/catch ConnectionException`), bukan error 500.

Buka `http://127.0.0.1:8000` di browser.

## Akun hasil seeding

| Email | Password | Role |
|---|---|---|
| `admin@pens.ac.id` | `password` | admin |
| `petugas1@pens.ac.id` | `password` | petugas |
| `petugas2@pens.ac.id` | `password` | petugas |

Data dummy: 5 kategori, 20 buku (judul+penerbit kurasi Indonesia), 15 anggota (email `@pens.ac.id`), 10 transaksi peminjaman.

## Perbedaan Model, View, dan Controller

Menurut pemahaman saya, Model bertugas mengelola data dan aturan terkait database, View hanya menampilkan tampilan HTML ke pengguna, dan Controller menjadi penghubung yang menerima request lalu meminta data ke Model sebelum mengirimkannya ke View. Ketiganya dipisah supaya kode tidak campur aduk dalam satu file, sehingga lebih mudah diubah dan dilacak saat ada error.

## Skenario demo UAS

Lihat `DEMO.md`.
