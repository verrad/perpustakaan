<?php
// Simple DB initializer — run once to create database and tables.
// Edit the credentials below to match `config.php` if needed.
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_PORT = 3306;
$SQL_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'init_db.sql';

if (!file_exists($SQL_FILE)) {
    http_response_code(500);
    exit('SQL file not found: ' . htmlspecialchars($SQL_FILE));
}

$sql = file_get_contents($SQL_FILE);
if ($sql === false) {
    http_response_code(500);
    exit('Failed to read SQL file.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, '', $DB_PORT);
    $conn->set_charset('utf8mb4');

    // Execute multiple statements
    if ($conn->multi_query($sql)) {
        do {
            // store_result is required to flush multi_query results
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    }

    echo "Success: SQL executed.\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
