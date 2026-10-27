<?php
require_once __DIR__ . '/../src/database.php';

session_start();

$message = '';
$error   = '';
$debugSql = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ============================================================
    // KERENTANAN SQL INJECTION:
    // Input pengguna langsung digabungkan ke dalam query SQL tanpa
    // sanitasi / prepared statement. Input seperti
    //   username: ' OR '1'='1
    // dapat memaksa kondisi WHERE selalu benar.
    // ============================================================
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = db_connect();

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $debugSql = $sql;

    $stmt = $conn->query($sql);

    if ($stmt) {
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $message = "Login berhasil sebagai <strong>" . htmlspecialchars($user['username']) . "</strong> (role: " . htmlspecialchars($user['role']) . ").";
        } else {
            $error = 'Username atau password salah.';
        }
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
    <title>Login — Student Portal Injection Lab</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 640px; margin: 3rem auto; padding: 0 1rem; color: #222; }
        a { color: #0b5fff; }
        label { display: block; margin: .75rem 0 .25rem; }
        input { width: 100%; padding: .5rem; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: 1rem; padding: .55rem 1.2rem; border: none; border-radius: 6px; background: #0b5fff; color: #fff; cursor: pointer; }
        .msg { margin-top: 1rem; padding: .8rem; border-radius: 6px; }
        .success { background: #e6f6e6; color: #1a7f37; }
        .error { background: #fdecec; color: #b00020; }
        .debug { margin-top: 1rem; padding: .8rem; background: #f4f4f4; border-radius: 6px; font-family: monospace; font-size: .85rem; word-break: break-all; }
    </style>
</head>
<body>
    <h1>Login</h1>
    <p><a href="index.php">&larr; Kembali ke beranda</a></p>

    <form method="post" action="login.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" autocomplete="off">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <button type="submit">Login</button>
    </form>

    <?php if ($message): ?>
        <div class="msg success"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="msg error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($debugSql): ?>
        <div class="debug"><strong>SQL yang dieksekusi:</strong> <?= htmlspecialchars($debugSql) ?></div>
    <?php endif; ?>

    <p style="margin-top:2rem;color:#888;font-size:.85rem;">Akun uji: <code>admin / admin123</code> atau <code>student1 / student123</code></p>
</body>
</html>
