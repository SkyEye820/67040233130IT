<?php
/**
 * การเชื่อมต่อ Database
 * Database: Hm04
 */

// ตั้งค่าการเชื่อมต่อ
$servername = "localhost";
$username = "it67040233130";
$password = "T6S0W8U1";
$dbname = "it67040233130";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สร้าง database ถ้ายังไม่มี
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // เลือก database
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// สร้างตารางถ้ายังไม่มี
$createTableSQL = "CREATE TABLE IF NOT EXISTS orders (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10, 2) NOT NULL,
    discount_rate INT(11) NOT NULL,
    member_rate INT(11) NOT NULL DEFAULT 0,
    total_discount_rate INT(11) NOT NULL,
    discount_money DECIMAL(10, 2) NOT NULL,
    net_price DECIMAL(10, 2) NOT NULL,
    is_member TINYINT(1) DEFAULT 0,
    level VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createTableSQL) === FALSE) {
    // ไม่หยุดการทำงาน ถ้าตารางมีอยู่แล้ว
}

// ตั้ง charset เป็น UTF-8
$conn->set_charset("utf8mb4");
?>
