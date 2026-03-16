<?php
require_once 'db_Fha.php';

// ตรวจสอบ ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: records_Fha.php?error=' . urlencode('ไม่พบ ID'));
    exit;
}

// ลบข้อมูล
try {
    $stmt = $pdo->prepare("DELETE FROM bmi_records WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    header('Location: records_Fha.php?success=' . urlencode('ลบข้อมูลสำเร็จ'));
    exit;
} catch (PDOException $e) {
    header('Location: records_Fha.php?error=' . urlencode('ลบข้อมูลไม่สำเร็จ: ' . $e->getMessage()));
    exit;
}
?>
