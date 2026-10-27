<?php
require_once __DIR__ . '/../src/database.php';

$checks = [];

// 1. Versi PHP
$checks['Versi PHP'] = PHP_VERSION . ' (' . (PHP_VERSION_ID >= 70200 ? 'OK' : 'TERLALU LAMA, butuh >= 7.2') . ')';

// 2. Ekstensi PDO SQLite
$checks['Ekstensi PDO SQLite'] = extension_loaded('pdo_sqlite') ? 'Terpasang' : 'TIDAK ADA — aktifkan extension=pdo_sqlite di php.ini';

// 3. Koneksi & inisialisasi database
$conn = db_connect();
$checks['Koneksi database (SQLite)'] = 'Berhasil (file: database/student_portal.sqlite)';

// 4. Jumlah baris tiap tabel
foreach (['users', 'students'] as $table) {
    $res = $conn->query("SELECT COUNT(*) FROM $table");
    if ($res) {
        $count = $res->fetchColumn();
        $checks["Tabel `$table`"] = "Ada ($count baris)";
    } else {
        $errInfo = $conn->errorInfo();
        $checks["Tabel `$table`"] = 'TIDAK ADA / query gagal: ' . $errInfo[2];
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnostik — Student Portal Injection Lab</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 640px; margin: 3rem auto; padding: 0 1rem; color: #222; }
        a { color: #0b5fff; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: .5rem .6rem; text-align: left; font-size: .9rem; }
        th { background: #f4f4f4; }
        code { background: #f4f4f4; padding: .1rem .3rem; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Diagnostik</h1>
    <p><a href="index.php">&larr; Kembali ke beranda</a></p>

    <table>
        <thead><tr><th>Item</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($checks as $item => $status): ?>
            <tr><td><?= htmlspecialchars($item) ?></td><td><?= htmlspecialchars($status) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p style="margin-top:1.5rem;color:#888;font-size:.85rem;">
        Database SQLite dibuat &amp; di-seed otomatis oleh <code>src/database.php</code>
        saat pertama kali diakses.
    </p>
</body>
</html>
