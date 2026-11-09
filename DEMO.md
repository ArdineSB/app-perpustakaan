# Skenario Demo UAS — app-perpustakaan

Skenario demo tertulis sesuai `pertemuan-11.md` bagian Skenario Demo. Siapkan **browser dan Postman** terbuka sebelum mulai, dan pastikan **dua server** sudah jalan (lihat `README.md`).

## Persiapan sebelum demo

1. Nyalakan MySQL (mis. lewat Laragon).
2. Jalankan `php artisan migrate:fresh --seed` supaya data demo konsisten.
3. Jalankan dua server:
   - `php artisan serve --port=8000` (untuk browser)
   - `php artisan serve --port=8011` (internal API, dibaca `INTERNAL_API_URL` — tanpa ini Dashboard/Laporan tampil kosong)
4. Login kredensial (hasil `UserSeeder`):
   - Admin: `admin@pens.ac.id` / `password`
   - Petugas: `petugas1@pens.ac.id` / `password`

## Urutan demo

1. **Login** — buka `/login`, masuk sebagai admin. Tunjukkan redirect ke Dashboard dan nama + role di navbar.
2. **Dashboard statistik** — tunjukkan 3 kartu (Total Buku, Total Anggota, Peminjaman Aktif). Jelaskan angka diambil lewat `Http::get()` ke `GET /api/stats`, bukan query database langsung.
3. **Tambah kategori** — buka `/categories`, tambah 1 kategori baru. Tunjukkan flash message sukses di index.
4. **Tambah buku** — buka `/books/create`, tambah 1 buku, pilih kategori dari dropdown (termasuk kategori langkah 3). Tunjukkan nama kategori tampil benar di index, bukan `category_id` mentah.
5. **Tambah anggota** — buka `/members/create`, tambah 1 anggota. (Opsional: tunjukkan validasi `nim`/`email` unik dengan data duplikat.)
6. **Buat peminjaman** — buka `/loans/create`, pilih anggota dan buku baru. Tunjukkan `user_id` petugas terisi otomatis dari sesi login.
7. **Daftar peminjaman** — buka `/loans`, tunjukkan transaksi baru berstatus `dipinjam` lengkap nama anggota/petugas/buku (hasil eager loading).
8. **Pengembalian** — klik tombol kembalikan di `/loans` (fitur web Tugas P7). Tunjukkan status berubah jadi `dikembalikan`.
9. **Laporan** — buka `/loans/report`, tunjukkan semua transaksi tampil. Jelaskan data diambil dari `GET /api/loans` via Laravel HTTP Client, bukan query Eloquent langsung.
10. **Postman** — jalankan live:
    - `GET http://127.0.0.1:8011/api/books` → JSON terstruktur (format `BookResource`) + pagination.
    - `GET http://127.0.0.1:8011/api/stats` → `total_buku`, `total_anggota`, `peminjaman_aktif`.
11. **Tanya jawab** — dosen memilih pertanyaan konseptual dari modul Pertemuan 11.

## Catatan

- Kalau Dashboard/Laporan tampil kosong: cek server port `8011` jalan atau tidak — bukan berarti fiturnya rusak (lihat penjelasan deadlock `php artisan serve` di modul P-10).
- Semua tugas mandiri yang memperkaya demo sudah dikerjakan: pengembalian web (P7), badge status (P7), search anggota (P5), profil + ganti password (P8).
