<?php
declare(strict_types=1);

session_start();

$DB_HOST = "127.0.0.1";
$DB_NAME = "library_app";
$DB_USER = "root";
$DB_PASS = ""; // ganti sesuai server kamu
$port = 3306;

try {
  $pdo = new PDO(
    "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
} catch (Throwable $e) {
  http_response_code(500);
  exit("DB error: " . htmlspecialchars($e->getMessage()));
}

function csrf_token(): string {
  if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf'];
}

function csrf_verify(?string $token): bool {
  return isset($_SESSION['csrf']) && is_string($token) && hash_equals($_SESSION['csrf'], $token);
}

function is_logged_in(): bool {
  return !empty($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header("Location: login.php");
    exit;
  }
}