<?php
require_once __DIR__ . '/../src/database.php';

$q = $_GET['q'] ?? '';
$rows = [];
$error = '';
$debugSql = '';

if ($q !== '') {
    // ============================================================
    // KERENTANAN SQL INJECTION:
    // Nilai pencarian langsung disisipkan ke klausa LIKE tanpa
    // sanitasi. Input seperti:
    //   ' UNION SELECT id, username, password, role, NULL FROM users --
    // dapat membocorkan data dari tabel lain.
    // ============================================================
    $conn = db_connect();

    $sql = "SELECT id, nim, name, major, gpa FROM students WHERE name LIKE '%$q%'";
    $debugSql = $sql;

    $stmt = $conn->query($sql);

    if ($stmt) {
        $rows = $stmt->fetchAll();
    } else {
        $errInfo = $conn->errorInfo();
        $error = 'Query gagal: ' . $errInfo[2];
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pencarian Mahasiswa — Student Portal Injection Lab</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 760px; margin: 3rem auto; padding: 0 1rem; color: #222; }
        a { color: #0b5fff; }
        input { width: 100%; padding: .5rem; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: .75rem; padding: .55rem 1.2rem; border: none; border-radius: 6px; background: #0b5fff; color: #fff; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: .5rem .6rem; text-align: left; font-size: .9rem; }
        th { background: #f4f4f4; }
        .error { margin-top: 1rem; padding: .8rem; background: #fdecec; color: #b00020; border-radius: 6px; }
        .debug { margin-top: 1rem; padding: .8rem; background: #f4f4f4; border-radius: 6px; font-family: monospace; font-size: .85rem; word-break: break-all; }
        .hint { margin-top: 2rem; color: #888; font-size: .85rem; }
    </style>
</head>
<body>
    <h1>Pencarian Mahasiswa</h1>
    <p><a href="index.php">&larr; Kembali ke beranda</a></p>

    <form method="get" action="search.php">
        <input type="text" name="q" placeholder="Cari nama mahasiswa..." value="<?= htmlspecialchars($q) ?>">
        <button type="submit">Cari</button>
    </form>

    <?php if ($q !== '' && $rows): ?>
        <table>
            <thead>
                <tr><th>ID</th><th>NIM</th><th>Nama</th><th>Jurusan</th><th>IPK</th></tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars((string)$row['id']) ?></td>
                    <td><?= htmlspecialchars((string)$row['nim']) ?></td>
                    <td><?= htmlspecialchars((string)$row['name']) ?></td>
                    <td><?= htmlspecialchars((string)$row['major']) ?></td>
                    <td><?= htmlspecialchars((string)$row['gpa']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($q !== '' && !$error): ?>
        <p style="margin-top:1rem;color:#555;">Tidak ada hasil untuk "<strong><?= htmlspecialchars($q) ?></strong>".</p>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($debugSql): ?>
        <div class="debug"><strong>SQL yang dieksekusi:</strong> <?= htmlspecialchars($debugSql) ?></div>
    <?php endif; ?>

    <p class="hint">Coba cari "a" untuk melihat semua nama yang mengandung huruf a.</p>
</body>
</html>
