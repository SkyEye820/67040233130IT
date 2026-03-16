<?php
// =====================
// ✅ ค่าการเชื่อมต่อจาก hosting
// =====================
define("DB_HOST", "localhost");
define("DB_USER", "it67040233130");
define("DB_PASS", "T6S0W8U1");
define("DB_NAME", "it67040233130");

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("❌ การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตั้งค่า charset
$conn->set_charset("utf8mb4");

echo "✅ เชื่อมต่อฐานข้อมูลสำเร็จ";
?>
