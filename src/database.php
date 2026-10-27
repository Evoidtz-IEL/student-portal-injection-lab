<?php
/**
 * Koneksi database untuk Student Portal Injection Lab.
 *
 * Menggunakan SQLite via PDO sehingga TIDAK perlu install MySQL/MariaDB.
 * Database dibuat & di-seed otomatis saat pertama kali diakses.
 *
 * PENTING: query di halaman login/search SENG AJA dibuat rentan terhadap
 * SQL Injection (string concatenation) untuk keperluan pembelajaran.
 * JANGAN tiru pola ini di aplikasi produksi — gunakan prepared statements.
 */

// Lokasi file database SQLite
define('DB_FILE', __DIR__ . '/../database/student_portal.sqlite');

/**
 * Membuka koneksi ke database SQLite.
 *
 * @return PDO
 */
function db_connect()
{
    $pdo = new PDO('sqlite:' . DB_FILE);

    // ERRMODE_SILENT: query() mengembalikan false saat error (bukan throw),
    // sehingga pesan error bisa ditampilkan untuk keperluan pembelajaran.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    db_init($pdo);

    return $pdo;
}

/**
 * Membuat tabel & seed data (hanya jika belum ada/kosong).
 *
 * @param PDO $pdo
 */
function db_init($pdo)
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id       INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role     TEXT NOT NULL DEFAULT 'student'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id    INTEGER PRIMARY KEY AUTOINCREMENT,
        nim   TEXT NOT NULL,
        name  TEXT NOT NULL,
        major TEXT NOT NULL,
        gpa   REAL NOT NULL
    )");

    // Seed akun pengguna (hanya jika tabel masih kosong)
    if ((int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0) {
        $pdo->exec("INSERT INTO users (username, password, role) VALUES
            ('admin',    'admin123',   'admin'),
            ('student1', 'student123', 'student'),
            ('student2', 'student123', 'student')");
    }

    // Seed data mahasiswa (hanya jika tabel masih kosong)
    if ((int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn() === 0) {
        $pdo->exec("INSERT INTO students (nim, name, major, gpa) VALUES
            ('20210001', 'Andi Saputra',    'Teknik Informatika', 3.75),
            ('20210002', 'Budi Santoso',    'Teknik Informatika', 3.20),
            ('20210003', 'Citra Lestari',   'Sistem Informasi',   3.85),
            ('20210004', 'Dewi Anggraini',  'Sistem Informasi',   3.40),
            ('20210005', 'Eko Prasetyo',    'Teknik Komputer',    2.95),
            ('20210006', 'Fitri Handayani', 'Teknik Informatika', 3.60),
            ('20210007', 'Galih Ramadhan',  'Sistem Informasi',   3.10),
            ('20210008', 'Hana Salsabila',  'Teknik Komputer',    3.50)");
    }
}
