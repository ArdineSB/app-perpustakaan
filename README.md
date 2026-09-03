# app-perpustakaan

Sistem Perpustakaan Digital Kampus — aplikasi web Laravel 12 untuk mengelola buku, anggota, dan transaksi peminjaman.

Project ini dikerjakan bertahap sepanjang mata kuliah Pemrograman Framework (PENS). Saat ini masih di tahap setup awal (Pertemuan 1): kerangka Laravel sudah jalan, fitur CRUD belum ditambahkan.

## Cara menjalankan secara lokal

Prasyarat: PHP >= 8.2, Composer, dan Git.

```bash
git clone https://github.com/ArdineSB/app-perpustakaan.git
cd app-perpustakaan
git checkout dev
composer install
copy .env.example .env
php artisan key:generate
php artisan serve
```

Lalu buka `http://127.0.0.1:8000` di browser. Halaman welcome Laravel akan tampil kalau setup berhasil.

Konfigurasi database ada di file `.env` (`DB_DATABASE=db_perpustakaan`). Koneksi database baru dipakai mulai Pertemuan 5.

## Perbedaan Model, View, dan Controller

Menurut pemahaman saya, Model bertugas mengelola data dan aturan terkait database, View hanya menampilkan tampilan HTML ke pengguna, dan Controller menjadi penghubung yang menerima request lalu meminta data ke Model sebelum mengirimkannya ke View. Ketiganya dipisah supaya kode tidak campur aduk dalam satu file, sehingga lebih mudah diubah dan dilacak saat ada error.
