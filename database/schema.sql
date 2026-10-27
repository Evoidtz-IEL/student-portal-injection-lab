-- ============================================================
-- Skema referensi (SQLite) untuk Student Portal Injection Lab
--
-- CATATAN: tabel & seed data dibuat OTOMATIS oleh src/database.php
-- saat pertama kali aplikasi diakses, jadi file ini TIDAK wajib
-- dijalankan. Ini hanya dokumentasi struktur.
--
-- Jika ingin membuat manual:
--   sqlite3 database/student_portal.sqlite < database/schema.sql
-- ============================================================

-- Tabel pengguna (dipakai halaman login)
CREATE TABLE IF NOT EXISTS users (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role     TEXT NOT NULL DEFAULT 'student'
);

-- Tabel data mahasiswa (dipakai halaman pencarian)
CREATE TABLE IF NOT EXISTS students (
    id    INTEGER PRIMARY KEY AUTOINCREMENT,
    nim   TEXT NOT NULL,
    name  TEXT NOT NULL,
    major TEXT NOT NULL,
    gpa   REAL NOT NULL
);

-- Seed: akun pengguna
INSERT INTO users (username, password, role) VALUES
    ('admin',    'admin123',   'admin'),
    ('student1', 'student123', 'student'),
    ('student2', 'student123', 'student');

-- Seed: data mahasiswa
INSERT INTO students (nim, name, major, gpa) VALUES
    ('20210001', 'Andi Saputra',    'Teknik Informatika', 3.75),
    ('20210002', 'Budi Santoso',    'Teknik Informatika', 3.20),
    ('20210003', 'Citra Lestari',   'Sistem Informasi',   3.85),
    ('20210004', 'Dewi Anggraini',  'Sistem Informasi',   3.40),
    ('20210005', 'Eko Prasetyo',    'Teknik Komputer',    2.95),
    ('20210006', 'Fitri Handayani', 'Teknik Informatika', 3.60),
    ('20210007', 'Galih Ramadhan',  'Sistem Informasi',   3.10),
    ('20210008', 'Hana Salsabila',  'Teknik Komputer',    3.50);
