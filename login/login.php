<?php
require __DIR__ . "/config.php";

if (is_logged_in()) {
  header("Location: dashboard.php");
  exit;
}

$err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_verify($_POST['csrf'] ?? null)) {
    $err = "CSRF token tidak valid.";
  } else {
    $email = trim((string)($_POST['email'] ?? ""));
    $pass  = (string)($_POST['password'] ?? "");

    if ($email === "" || $pass === "") {
      $err = "Email dan password wajib diisi.";
    } else {
      $stmt = $pdo->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ? LIMIT 1");
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if (!$user || !password_verify($pass, $user['password_hash'])) {
        $err = "Email atau password salah.";
      } else {
        $_SESSION['user'] = [
          "id" => $user["id"],
          "name" => $user["name"],
          "email" => $user["email"],
        ];
        header("Location: dashboard.php");
        exit;
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
  <title>Login</title>
  <link rel="stylesheet" href="loginstyle.css" />
</head>
<body>
  <div class="shell">
    <div class="hero">
      <h1>Welcome back</h1>
      <p>Login dulu. Sistemnya aman, yang tidak aman biasanya manusianya.</p>
      <div class="badges">
        <div class="badge">Modern Glass UI</div>
        <div class="badge">Secure Session</div>
        <div class="badge">Fast</div>
      </div>
    </div>

    <div class="card">
      <h2>Sign In</h2>
      <small>Masukkan akun kamu.</small>

      <?php if ($err): ?><div class="alert"><?= htmlspecialchars($err) ?></div><?php endif; ?>

      <form class="form" method="POST" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>"/>

        <div class="row">
          <label>Email</label>
          <input name="email" type="email" placeholder="email@contoh.com" required />
        </div>

        <div class="row">
          <label>Password</label>
          <input name="password" type="password" placeholder="Password" required />
        </div>

        <button class="btn" type="submit">Login</button>
      </form>

      <a class="link" href="register.php">Belum punya akun? Register</a>
    </div>
  </div>
</body>
</html>