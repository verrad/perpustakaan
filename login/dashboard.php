<?php
require __DIR__ . "/config.php";
require_login();
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="loginstyle.css" />
</head>
<body>
  <div class="shell">
    <div class="hero">
      <h1>Dashboard</h1>
      <p>Kamu berhasil login. Selamat, sistem ini percaya sama kamu.</p>
      <div class="badges">
        <div class="badge">User: <?= htmlspecialchars($user['name']) ?></div>
        <div class="badge"><?= htmlspecialchars($user['email']) ?></div>
      </div>
    </div>

    <div class="card">
      <h2>Halo, <?= htmlspecialchars($user['name']) ?> 👋</h2>
      <small>Ini halaman yang cuma bisa diakses setelah login.</small>
      <div style="margin-top:16px">
        <a class="link" href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</body>
</html>