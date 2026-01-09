<?php
require __DIR__ . "/config.php";

$err = "";
$ok  = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['csrf'] ?? null)) {
    $err = "CSRF token tidak valid.";
  } else {
    $name = trim((string)($_POST['name'] ?? ""));
    $email = trim((string)($_POST['email'] ?? ""));
    $pass = (string)($_POST['password'] ?? "");

    if ($name === "" || $email === "" || $pass === "") {
      $err = "Semua field wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $err = "Email tidak valid.";
    } elseif (strlen($pass) < 6) {
      $err = "Password minimal 6 karakter.";
    } else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);

      try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        $ok = "Akun berhasil dibuat. Silakan login.";
      } catch (Throwable $e) {
        if (str_contains($e->getMessage(), "Duplicate")) $err = "Email sudah terdaftar.";
        else $err = "Gagal membuat akun.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link rel="stylesheet" href="loginstyle.css" />
</head>
<body>
  <div class="shell">
    <div class="hero">
      <h1>Login Dulu</h1>
      <p>Jika belum memiliki akun, silahkan daftar terlebih dahulu</p>
      <div class="badges">
        <div class="badge">Gradient: #d25d79 → #fdcdb9</div>
        <div class="badge">PDO + password_hash</div>
        <div class="badge">CSRF</div>
      </div>
    </div>

    <div class="card">
      <h2>Buat Akun</h2>
      <small>Isi data kamu dengan benar (sekali-kali).</small>

      <?php if ($err): ?><div class="alert"><?= htmlspecialchars($err) ?></div><?php endif; ?>
      <?php if ($ok): ?><div class="alert"><?= htmlspecialchars($ok) ?></div><?php endif; ?>

      <form class="form" method="POST" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>"/>

        <div class="row">
          <label>Nama</label>
          <input name="name" placeholder="Nama" required />
        </div>

        <div class="row">
          <label>Email</label>
          <input name="email" type="email" placeholder="email@contoh.com" required />
        </div>

        <div class="row">
          <label>Password</label>
          <input name="password" type="password" placeholder="Minimal 6 karakter" required />
        </div>

        <button class="btn" type="submit">Create Account</button>
      </form>

      <a class="link" href="login.php">Sudah punya akun? Login</a>
    </div>
  </div>
</body>
</html>