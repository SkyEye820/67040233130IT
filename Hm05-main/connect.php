<?php
/**
 * ไฟล์เชื่อมต่อฐานข้อมูล MySQL
 * ใช้ได้ร่วมกับทุกไฟล์ PHP ในโปรเจกต์
 */

// =====================================================
// ✅ ตั้งค่าฐานข้อมูล
// =====================================================
define("DB_HOST", "localhost");
define("DB_USER", "it67040233130");
define("DB_PASS", "T6S0W8U1");
define("DB_NAME", "it67040233130");

// =====================================================
// 🔗 สร้างการเชื่อมต่อ MySQLi
// =====================================================
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// =====================================================
// ❌ ตรวจสอบการเชื่อมต่อ
// =====================================================
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        'status' => 'error',
        'message' => '❌ เชื่อมต่อฐานข้อมูลไม่สำเร็จ',
        'error' => $conn->connect_error
    ]));
}

// =====================================================
// ⚙️ ตั้งค่า Charset เป็น UTF-8
// =====================================================
$conn->set_charset("utf8mb4");

// =====================================================
// 🛡️ สร้างฟังก์ชัน SQL Injection Protection
// =====================================================
function sanitize($input) {
    global $conn;
    return $conn->real_escape_string(trim($input));
}

// =====================================================
// 📊 สร้างฟังก์ชัน ดึงข้อมูลชั้นเดียว
// =====================================================
function getRecord($table, $field, $value) {
    global $conn;
    $sql = "SELECT * FROM `$table` WHERE `$field` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

// =====================================================
// 📝 สร้างฟังก์ชัน บันทึกข้อมูลลงตาราง students
// =====================================================
function saveStudent($studentId, $prefix, $firstname, $lastname, $fullName) {
    global $conn;
    
    $sql = "INSERT INTO students (student_id, prefix, firstname, lastname, full_name) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            prefix=VALUES(prefix), firstname=VALUES(firstname), 
            lastname=VALUES(lastname), full_name=VALUES(full_name), 
            updated_at=NOW()";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => false, 'error' => $conn->error];
    }
    
    $stmt->bind_param("sssss", $studentId, $prefix, $firstname, $lastname, $fullName);
    $result = $stmt->execute();
    $stmt->close();
    
    return ['success' => $result];
}

// =====================================================
// 📜 สร้างฟังก์ชัน บันทึกประวัติการแยกชื่อ
// =====================================================
function saveNameHistory($studentId, $inputName, $prefix, $firstname, $lastname) {
    global $conn;
    
    $sql = "INSERT INTO name_history (student_id, input_name, prefix, firstname, lastname) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['success' => false, 'error' => $conn->error];
    }
    
    $stmt->bind_param("sssss", $studentId, $inputName, $prefix, $firstname, $lastname);
    $result = $stmt->execute();
    $stmt->close();
    
    return ['success' => $result];
}

// =====================================================
// 📋 สร้างฟังก์ชัน ดึงประวัติการแยกชื่อ
// =====================================================
function getNameHistory($studentId, $limit = 10) {
    global $conn;
    
    $sql = "SELECT * FROM name_history 
            WHERE student_id = ? 
            ORDER BY processed_at DESC 
            LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $studentId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    $history = [];
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    return $history;
}

// ✅ เชื่อมต่อสำเร็จ
?>
