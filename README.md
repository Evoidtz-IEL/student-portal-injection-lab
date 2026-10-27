# Student Portal Injection Lab

Website latihan keamanan aplikasi berbasis PHP yang **sengaja dibuat rentan
terhadap SQL Injection** untuk keperluan pembelajaran (tugas DevSecOps).

> ⚠️ Aplikasi ini hanya untuk belajar di lingkungan lokal. Jangan di-deploy ke
> server publik.

## Fitur

- **Login** (`public/login.php`) — rentan terhadap authentication bypass.
- **Pencarian Mahasiswa** (`public/search.php`) — rentan terhadap data
  extraction via UNION-based injection.
- **Diagnostik** (`public/diagnostic.php`) — cek koneksi DB & environment.

## Struktur

```
student-portal-injection-lab/
├── database/
│   └── schema.sql        # skema referensi (SQLite)
├── docs/
│   └── findings.md       # dokumentasi kerentanan & perbaikan
├── public/
│   ├── index.php         # beranda
│   ├── login.php         # form login (rentan)
│   ├── search.php        # pencarian (rentan)
│   └── diagnostic.php    # cek environment
└── src/
    └── database.php      # koneksi DB (SQLite, auto-seed)
```

## Kebutuhan

- PHP 7.2 atau lebih baru, dengan ekstensi `pdo_sqlite` (sudah aktif di
  Laravel Herd).
- **Tidak perlu MySQL/MariaDB** — database memakai SQLite dan dibuat otomatis.

## Cara Menjalankan

1. **Jalankan server PHP built-in:**

   ```bash
   php -S localhost:8000 -t public
   ```

2. **Buka browser:** http://localhost:8000

Database SQLite (`database/student_portal.sqlite`) beserta tabel & seed data
dibuat **otomatis** saat pertama kali halaman diakses.

> 💡 Kalau pakai Laravel Herd, kamu juga bisa: `herd link` lalu buka lewat
> domain `.test` — tidak wajib.

## Akun Uji

| Username  | Password    | Role    |
|-----------|-------------|---------|
| admin     | admin123    | admin   |
| student1  | student123  | student |
| student2  | student123  | student |

## Contoh Payload SQL Injection

- **Login bypass** — username: `' OR '1'='1` (password bebas)
- **Data extraction** — kolom pencarian:
  `' UNION SELECT id, username, password, role, NULL FROM users --`

## Dokumentasi Kerentanan

Lihat `docs/findings.md` untuk daftar kerentanan, contoh payload, dan cara
perbaikannya.
