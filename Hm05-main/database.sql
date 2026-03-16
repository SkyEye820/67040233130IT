-- =============================================
-- Database: it67040233130
-- สร้างตารางสำหรับเก็บข้อมูลชื่อ-สกุล
-- =============================================

-- สร้างตาราง students สำหรับเก็บข้อมูลนักศึกษา
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID หลัก',
  `student_id` VARCHAR(20) NOT NULL UNIQUE COMMENT 'รหัสนักศึกษา',
  `prefix` VARCHAR(50) COMMENT 'คำนำหน้า',
  `firstname` VARCHAR(100) NOT NULL COMMENT 'ชื่อ',
  `lastname` VARCHAR(100) COMMENT 'สกุล',
  `full_name` VARCHAR(255) COMMENT 'ชื่อ-สกุลเต็ม',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัปเดต',
  INDEX `idx_student_id` (`student_id`),
  INDEX `idx_firstname` (`firstname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางข้อมูลนักศึกษา';

-- =============================================
-- สร้างตาราง name_history สำหรับเก็บประวัติการแยกชื่อ
-- =============================================
CREATE TABLE IF NOT EXISTS `name_history` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID หลัก',
  `student_id` VARCHAR(20) COMMENT 'รหัสนักศึกษา',
  `input_name` VARCHAR(255) COMMENT 'ชื่อที่กรอก',
  `prefix` VARCHAR(50) COMMENT 'คำนำหน้าที่แยก',
  `firstname` VARCHAR(100) COMMENT 'ชื่อที่แยก',
  `lastname` VARCHAR(100) COMMENT 'สกุลที่แยก',
  `processed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ประมวลผล',
  INDEX `idx_student_id` (`student_id`),
  INDEX `idx_processed_at` (`processed_at`),
  FOREIGN KEY (`student_id`) REFERENCES `students`(`student_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ประวัติการแยกชื่อ-สกุล';
