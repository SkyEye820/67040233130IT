<?php
require __DIR__ . "/_config.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $conn->set_charset("utf8mb4");
} catch (Throwable $e) {
  header("Content-Type: application/json; charset=utf-8");
  http_response_code(500);
  echo json_encode([
    "error" => "DB connection failed",
    "message" => $e->getMessage(),
    "check" => [
      "DB_HOST" => DB_HOST,
      "DB_USER" => DB_USER,
      "DB_NAME" => DB_NAME
    ],
    "hint" => "แก้ค่าใน api/_config.php ให้ตรงกับ DB ของ hosting"
  ], JSON_UNESCAPED_UNICODE);
  exit;
}
