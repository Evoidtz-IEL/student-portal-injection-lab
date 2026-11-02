<?php
$host = $_GET['host'] ?? '';
$output = '';
$command = '';

if ($host !== '') {
    // ============================================================
    // KERENTANAN COMMAND INJECTION:
    // Input pengguna langsung digabungkan ke perintah shell tanpa
    // sanitasi. Input seperti:
    //   127.0.0.1; whoami
    // akan menjalankan perintah tambahan di server.
    // ============================================================
    $command = "ping -c 4 " . $host;
    $output = shell_exec($command . " 2>&1");
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ping — Student Portal Injection Lab</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 720px; margin: 3rem auto; padding: 0 1rem; color: #222; }
        a { color: #0b5fff; }
        input { width: 100%; padding: .5rem; box-sizing: border-box; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: .75rem; padding: .55rem 1.2rem; border: none; border-radius: 6px; background: #0b5fff; color: #fff; cursor: pointer; }
        .debug { margin-top: 1rem; padding: .8rem; background: #f4f4f4; border-radius: 6px; font-family: monospace; font-size: .85rem; word-break: break-all; }
        pre { background: #111; color: #0f0; padding: 1rem; border-radius: 6px; overflow-x: auto; font-size: .85rem; min-height: 2rem; }
        .hint { margin-top: 2rem; color: #888; font-size: .85rem; }
        code { background: #f4f4f4; padding: .1rem .3rem; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Ping (Diagnostik Jaringan)</h1>
    <p><a href="index.php">&larr; Kembali ke beranda</a></p>

    <form method="get" action="ping.php">
        <input type="text" name="host" placeholder="Masukkan host, mis. 127.0.0.1" value="<?= htmlspecialchars($host) ?>">
        <button type="submit">Ping</button>
    </form>

    <?php if ($command): ?>
        <div class="debug"><strong>Perintah yang dieksekusi:</strong> <?= htmlspecialchars($command) ?></div>
    <?php endif; ?>

    <?php if ($host !== ''): ?>
        <pre><?= htmlspecialchars($output) ?></pre>
    <?php endif; ?>

    <p class="hint">
        Coba: <code>127.0.0.1; whoami</code> atau <code>; ls -la</code>
    </p>
</body>
</html>
