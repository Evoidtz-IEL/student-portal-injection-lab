<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Portal Injection Lab</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 720px; margin: 3rem auto; padding: 0 1rem; color: #222; }
        a { color: #0b5fff; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 1.25rem; margin-bottom: 1rem; }
        .card h2 { margin: 0 0 .4rem; }
        .card p { margin: 0; color: #555; }
    </style>
</head>
<body>
    <h1>Student Portal Injection Lab</h1>
    <p>Website latihan keamanan aplikasi — sengaja dibuat <strong>rentan terhadap SQL Injection</strong> untuk keperluan pembelajaran DevSecOps.</p>

    <div class="card">
        <h2><a href="login.php">Halaman Login</a></h2>
        <p>Uji SQL Injection pada form login (authentication bypass).</p>
    </div>
    <div class="card">
        <h2><a href="search.php">Pencarian Mahasiswa</a></h2>
        <p>Uji SQL Injection pada fitur pencarian (data extraction).</p>
    </div>
    <div class="card">
        <h2><a href="diagnostic.php">Diagnostik</a></h2>
        <p>Cek koneksi database dan konfigurasi environment.</p>
    </div>
</body>
</html>
