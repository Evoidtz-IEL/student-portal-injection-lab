# Temuan Kerentanan — Student Portal Injection Lab

Dokumen ini mencatat kerentanan yang **sengaja ditanam** di aplikasi untuk
keperluan pembelajaran. Setiap temuan disertai lokasi, penyebab, contoh
eksploitasi, dan perbaikan yang disarankan.

> Aplikasi memakai database **SQLite** (via PDO). Konsep SQL Injection yang
> dijelaskan di sini berlaku sama untuk MySQL/MariaDB — hanya detail sintaks
> komentar (`--` vs `#`) yang bisa berbeda.

---

## 1. SQL Injection — Authentication Bypass (login.php)

- **Lokasi:** `public/login.php`
- **Tingkat keparahan:** Kritis
- **Penyebab:** Input `username` dan `password` langsung digabungkan ke query
  SQL tanpa sanitasi maupun prepared statement.

```php
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

### Contoh eksploitasi

Isi form login dengan:

- **Username:** `' OR '1'='1`
- **Password:** `' OR '1'='1`

Query yang terbentuk menjadi:

```sql
SELECT * FROM users WHERE username = '' OR '1'='1' AND password = '' OR '1'='1'
```

Karena `'1'='1'` selalu benar, kondisi `WHERE` terpenuhi dan penyerang bisa
masuk **tanpa kredensial valid**.

### Perbaikan

Gunakan **prepared statement**:

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->execute([$username, $password]);
$user = $stmt->fetch();
```

---

## 2. SQL Injection — Data Extraction via UNION (search.php)

- **Lokasi:** `public/search.php`
- **Tingkat keparahan:** Kritis
- **Penyebab:** Nilai pencarian `$_GET['q']` langsung disisipkan ke klausa
  `LIKE` tanpa sanitasi.

```php
$sql = "SELECT id, nim, name, major, gpa FROM students WHERE name LIKE '%$q%'";
```

### Contoh eksploitasi

Pada kolom pencarian, masukkan:

```
' UNION SELECT id, username, password, role, NULL FROM users --
```

Query yang terbentuk menjadi:

```sql
SELECT id, nim, name, major, gpa FROM students WHERE name LIKE '%' UNION SELECT id, username, password, role, NULL FROM users -- %'
```

Hasilnya: data `username` dan `password` dari tabel `users` ikut terbongkar di
kolom NIM dan Nama pada tabel hasil.

### Perbaikan

Gunakan **prepared statement** untuk nilai `LIKE`:

```php
$stmt = $pdo->prepare("SELECT id, nim, name, major, gpa FROM students WHERE name LIKE ?");
$stmt->execute(["%$q%"]);
$rows = $stmt->fetchAll();
```

---

## 3. Kebocoran Informasi — Pesan Error SQL (login.php & search.php)

- **Lokasi:** `public/login.php`, `public/search.php`
- **Tingkat keparahan:** Sedang
- **Penyebab:** Pesan error database (`errorInfo()[2]`) dan query mentah
  ditampilkan langsung ke pengguna. Ini mempermudah penyerang menyusun
  payload, tetapi **sengaja ditampilkan** di lab ini untuk membantu pemahaman.

### Perbaikan

Di produksi: sembunyikan detail error (log di server), tampilkan pesan umum
seperti "Terjadi kesalahan".

---

## 4. Penyimpanan Password Plaintext (src/database.php)

- **Lokasi:** `src/database.php` (bagian seed data)
- **Tingkat keparahan:** Tinggi
- **Penyebab:** Password disimpan sebagai teks biasa (`admin123`, dll).

### Perbaikan

Hash password dengan `password_hash()` / `password_verify()` (bcrypt/argon2).

---

## Rekomendasi Umum

1. Selalu pakai **prepared statements** untuk semua query berparameter.
2. Terapkan **prinsip least privilege** untuk akun database aplikasi.
3. Hash password; jangan pernah simpan plaintext.
4. Sembunyikan pesan error internal dari pengguna.
5. Terapkan **input validation** dan **output encoding**.
