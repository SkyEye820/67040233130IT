-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS registration_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- เลือกฐานข้อมูล
USE registration_db;

-- สร้างตาราง
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    course VARCHAR(255) NOT NULL,
    food TEXT,
    type VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- สร้าง Index สำหรับเร็ว
ALTER TABLE registrations ADD INDEX idx_email (email);
ALTER TABLE registrations ADD INDEX idx_created_at (created_at);
