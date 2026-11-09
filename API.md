# Dokumentasi REST API — app-perpustakaan

Base URL: `http://127.0.0.1:8000/api`

Header yang dipakai saat uji: `Accept: application/json`. Body request dikirim sebagai JSON (`Content-Type: application/json`). Endpoint API Pertemuan 9 sengaja publik (belum pakai token).

Response di bawah ini hasil uji sungguhan terhadap server lokal (bukan contoh rekaan). Pagination Laravel menambahkan `links` dan `meta` pada endpoint list.

## Ringkasan 11 endpoint

| Method | URL | Body (jika ada) | Status |
|---|---|---|---|
| GET | `/api/books` | query opsional: `judul`, `category_id` | 200 |
| GET | `/api/books/{id}` | - | 200 / 404 |
| POST | `/api/books` | JSON buku | 201 / 422 |
| PUT | `/api/books/{id}` | JSON buku | 200 / 404 |
| DELETE | `/api/books/{id}` | - | 200 / 404 |
| GET | `/api/members` | - | 200 |
| GET | `/api/members/{id}` | - | 200 / 404 |
| GET | `/api/loans` | - | 200 |
| POST | `/api/loans` | JSON transaksi + `book_ids` | 201 / 422 |
| PUT | `/api/loans/{id}/kembalikan` | - | 200 / 404 |
| GET | `/api/stats` | - | 200 |

---

## 1. GET `/api/books`

Filter yang diuji: tanpa query (semua), `?judul=laskar`, `?category_id=3`.

**Status:** `200 OK`

```json
{
  "data": [
    {
      "id": 1,
      "judul": "Kisah Cinta Titan",
      "penulis": "Titanio",
      "penerbit": "Tio Terbit",
      "tahun_terbit": "2025",
      "isbn": null,
      "stok": 1,
      "sampul": null,
      "category": {
        "id": 3,
        "nama_kategori": "Fiksi"
      },
      "created_at": "2026-09-24T05:01:57.000000Z",
      "updated_at": "2026-09-24T05:01:57.000000Z"
    },
    {
      "id": 2,
      "judul": "Laskar Pelangi",
      "penulis": "Andrea Hirata",
      "penerbit": "Bentang Pustaka",
      "tahun_terbit": "2005",
      "isbn": "9789793062792",
      "stok": 5,
      "sampul": null,
      "category": {
        "id": 3,
        "nama_kategori": "Fiksi"
      },
      "created_at": "2026-09-27T13:00:08.000000Z",
      "updated_at": "2026-09-27T13:00:08.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/books?page=1",
    "last": "http://127.0.0.1:8000/api/books?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "page": null,
        "active": false
      },
      {
        "url": "http://127.0.0.1:8000/api/books?page=1",
        "label": "1",
        "page": 1,
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "page": null,
        "active": false
      }
    ],
    "path": "http://127.0.0.1:8000/api/books",
    "per_page": 10,
    "to": 2,
    "total": 2
  }
}
```

---

## 2. GET `/api/books/{id}`

Contoh: `GET /api/books/2`

**Status sukses:** `200 OK`

```json
{
  "data": {
    "id": 2,
    "judul": "Laskar Pelangi",
    "penulis": "Andrea Hirata",
    "penerbit": "Bentang Pustaka",
    "tahun_terbit": "2005",
    "isbn": "9789793062792",
    "stok": 5,
    "sampul": null,
    "category": {
      "id": 3,
      "nama_kategori": "Fiksi"
    },
    "created_at": "2026-09-27T13:00:08.000000Z",
    "updated_at": "2026-09-27T13:00:08.000000Z"
  }
}
```

**Status gagal** (`GET /api/books/999`): `404 Not Found`

```json
{
  "message": "No query results for model [App\\Models\\Book] 999",
  "exception": "Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException"
}
```

---

## 3. POST `/api/books`

**Body sukses:**

```json
{
  "judul": "API Test Buku P9",
  "penulis": "Penulis Uji",
  "penerbit": "Penerbit Uji",
  "tahun_terbit": 2024,
  "isbn": "9780000000001",
  "stok": 3,
  "category_id": 3
}
```

**Status sukses:** `201 Created`

```json
{
  "data": {
    "id": 3,
    "judul": "API Test Buku P9",
    "penulis": "Penulis Uji",
    "penerbit": "Penerbit Uji",
    "tahun_terbit": 2024,
    "isbn": "9780000000001",
    "stok": 3,
    "sampul": null,
    "category": {
      "id": 3,
      "nama_kategori": "Fiksi"
    },
    "created_at": "2026-10-29T03:24:54.000000Z",
    "updated_at": "2026-10-29T03:24:54.000000Z"
  }
}
```

**Body gagal:** `{}` (kosong)

**Status gagal:** `422 Unprocessable Entity`

```json
{
  "message": "Judul buku wajib diisi. (and 5 more errors)",
  "errors": {
    "judul": [
      "Judul buku wajib diisi."
    ],
    "penulis": [
      "Nama penulis wajib diisi."
    ],
    "penerbit": [
      "Nama penerbit wajib diisi."
    ],
    "tahun_terbit": [
      "Tahun terbit wajib diisi."
    ],
    "stok": [
      "Stok wajib diisi."
    ],
    "category_id": [
      "Kategori wajib dipilih."
    ]
  }
}
```

Buku id `3` dari uji create ini sudah dihapus lagi lewat `DELETE /api/books/3` (lihat endpoint 5).

---

## 4. PUT `/api/books/{id}`

Contoh: `PUT /api/books/3`

**Body:**

```json
{
  "judul": "API Test Buku P9 Edited",
  "penulis": "Penulis Uji",
  "penerbit": "Penerbit Uji",
  "tahun_terbit": 2025,
  "isbn": "9780000000001",
  "stok": 5,
  "category_id": 1
}
```

**Status sukses:** `200 OK`

```json
{
  "data": {
    "id": 3,
    "judul": "API Test Buku P9 Edited",
    "penulis": "Penulis Uji",
    "penerbit": "Penerbit Uji",
    "tahun_terbit": 2025,
    "isbn": "9780000000001",
    "stok": 5,
    "sampul": null,
    "category": {
      "id": 1,
      "nama_kategori": "Sains"
    },
    "created_at": "2026-10-29T03:24:54.000000Z",
    "updated_at": "2026-10-29T03:24:55.000000Z"
  }
}
```

---

## 5. DELETE `/api/books/{id}`

Contoh: `DELETE /api/books/3`

**Status sukses:** `200 OK`

```json
{
  "message": "Buku berhasil dihapus."
}
```

---

## 6. GET `/api/members`

**Status:** `200 OK`

```json
{
  "data": [
    {
      "id": 1,
      "nama": "Joko Widodo",
      "nim": "3125600096",
      "email": "joko.subianto@pens.com",
      "nomor_telepon": "08123456678",
      "alamat": "Jl. Keputih",
      "status": "aktif",
      "created_at": "2026-09-26T07:26:55.000000Z",
      "updated_at": "2026-09-26T08:13:12.000000Z"
    },
    {
      "id": 4,
      "nama": "Siti Aminah",
      "nim": "3125600102",
      "email": "siti.aminah@pens.ac.id",
      "nomor_telepon": "081234567891",
      "alamat": "Jl. Raya ITS, Sukolilo, Surabaya",
      "status": "aktif",
      "created_at": "2026-09-27T13:00:16.000000Z",
      "updated_at": "2026-09-27T13:00:16.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/members?page=1",
    "last": "http://127.0.0.1:8000/api/members?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "page": null,
        "active": false
      },
      {
        "url": "http://127.0.0.1:8000/api/members?page=1",
        "label": "1",
        "page": 1,
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "page": null,
        "active": false
      }
    ],
    "path": "http://127.0.0.1:8000/api/members",
    "per_page": 10,
    "to": 2,
    "total": 2
  }
}
```

---

## 7. GET `/api/members/{id}`

Contoh: `GET /api/members/1`

**Status sukses:** `200 OK`

```json
{
  "data": {
    "id": 1,
    "nama": "Joko Widodo",
    "nim": "3125600096",
    "email": "joko.subianto@pens.com",
    "nomor_telepon": "08123456678",
    "alamat": "Jl. Keputih",
    "status": "aktif",
    "created_at": "2026-09-26T07:26:55.000000Z",
    "updated_at": "2026-09-26T08:13:12.000000Z"
  }
}
```

---

## 8. GET `/api/loans`

**Status:** `200 OK`

```json
{
  "data": [
    {
      "id": 7,
      "member": {
        "id": 1,
        "nama": "Joko Widodo"
      },
      "petugas": {
        "id": 1,
        "name": "Petugas Satu"
      },
      "tanggal_pinjam": "2026-10-08",
      "tanggal_kembali": "2026-10-09",
      "tanggal_dikembalikan": null,
      "status": "dipinjam",
      "books": [
        {
          "id": 2,
          "judul": "Laskar Pelangi"
        }
      ]
    },
    {
      "id": 8,
      "member": {
        "id": 1,
        "nama": "Joko Widodo"
      },
      "petugas": {
        "id": 1,
        "name": "Petugas Satu"
      },
      "tanggal_pinjam": "2026-10-29",
      "tanggal_kembali": "2026-11-05",
      "tanggal_dikembalikan": "2026-10-29",
      "status": "dikembalikan",
      "books": [
        {
          "id": 2,
          "judul": "Laskar Pelangi"
        }
      ]
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/loans?page=1",
    "last": "http://127.0.0.1:8000/api/loans?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "page": null,
        "active": false
      },
      {
        "url": "http://127.0.0.1:8000/api/loans?page=1",
        "label": "1",
        "page": 1,
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "page": null,
        "active": false
      }
    ],
    "path": "http://127.0.0.1:8000/api/loans",
    "per_page": 10,
    "to": 2,
    "total": 2
  }
}
```

---

## 9. POST `/api/loans`

**Body:**

```json
{
  "member_id": 1,
  "user_id": 1,
  "tanggal_pinjam": "2026-10-29",
  "tanggal_kembali": "2026-11-05",
  "book_ids": [2]
}
```

`user_id` wajib di body karena API ini tidak memakai session `auth()`.

**Status sukses:** `201 Created` — `status` transaksi terbaca `"dipinjam"` (bukan `null`).

```json
{
  "data": {
    "id": 8,
    "member": {
      "id": 1,
      "nama": "Joko Widodo"
    },
    "petugas": {
      "id": 1,
      "name": "Petugas Satu"
    },
    "tanggal_pinjam": "2026-10-29",
    "tanggal_kembali": "2026-11-05",
    "tanggal_dikembalikan": null,
    "status": "dipinjam",
    "books": [
      {
        "id": 2,
        "judul": "Laskar Pelangi"
      }
    ]
  }
}
```

---

## 10. PUT `/api/loans/{id}/kembalikan`

Contoh: `PUT /api/loans/8/kembalikan`

**Status sukses:** `200 OK`

```json
{
  "data": {
    "id": 8,
    "member": {
      "id": 1,
      "nama": "Joko Widodo"
    },
    "petugas": {
      "id": 1,
      "name": "Petugas Satu"
    },
    "tanggal_pinjam": "2026-10-29",
    "tanggal_kembali": "2026-11-05",
    "tanggal_dikembalikan": "2026-10-29",
    "status": "dikembalikan",
    "books": [
      {
        "id": 2,
        "judul": "Laskar Pelangi"
      }
    ]
  }
}
```

---

## 11. GET `/api/stats`

**Status:** `200 OK`

```json
{
  "total_buku": 2,
  "total_anggota": 2,
  "peminjaman_aktif": 1
}
```
