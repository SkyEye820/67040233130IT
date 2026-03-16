<?php
// ===== การตั้งค่าฐานข้อมูล =====
$host = "localhost";
$dbname = "it67040233130";
$username = "it67040233130";
$password = "T6S0W8U1";

// ===== การเชื่อมต่อฐานข้อมูล =====
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ===== ข้อมูลการเชื่อมต่อ phpMyAdmin =====
define('PHPMYADMIN_URL', 'http://localhost/phpmyadmin');
define('DB_HOST', $host);
define('DB_NAME', $dbname);
define('DB_USER', $username);
