<?php
// เรียกไฟล์เชื่อมต่อฐานข้อมูล
require_once 'connect.php';

// ฟังก์ชันแยกชื่อ-สกุล
function splitThaiName($fullName)
{
    $prefixes = [
        "นาย","นาง","นางสาว","เด็กชาย","เด็กหญิง",
        "น.ส.","ด.ช.","ด.ญ.","ร.ต.ต.","ด.ต.","มรว.","ผศ.","ดร."
    ];

    $result = ["prefix"=>"","firstname"=>"","lastname"=>""];

    $fullName = trim(preg_replace('/\s+/', ' ', $fullName));

    usort($prefixes, function ($a, $b) {
        return mb_strlen($b,'UTF-8') - mb_strlen($a,'UTF-8');
    });

    foreach ($prefixes as $p) {
        $len = mb_strlen($p,'UTF-8');
        if (mb_substr($fullName,0,$len,'UTF-8') === $p) {
            $result['prefix'] = $p;
            $fullName = ltrim(mb_substr($fullName,$len,null,'UTF-8'));
            break;
        }
    }

    $parts = explode(' ', $fullName);

    if (count($parts) === 1) {
        $result['firstname'] = $parts[0];
        return $result;
    }

    $result['firstname'] = $parts[0];
    $result['lastname'] = implode(' ', array_slice($parts,1));
    return $result;
}

$data = ["prefix"=>"","firstname"=>"","lastname"=>""];
$studentId = "67040233130";
$message = "";

if (isset($_POST['fullname']) && !empty($_POST['fullname'])) {
    $fullName = sanitize($_POST['fullname']);
    $data = splitThaiName($fullName);
    
    // บันทึกข้อมูลลงฐานข้อมูล
    $saveStudent = saveStudent($studentId, $data['prefix'], $data['firstname'], $data['lastname'], $fullName);
    $saveHistory = saveNameHistory($studentId, $fullName, $data['prefix'], $data['firstname'], $data['lastname']);
    
    if ($saveStudent['success'] && $saveHistory['success']) {
        $message = "✅ บันทึกข้อมูลเรียบร้อยแล้ว";
    } else {
        $message = "⚠️ เกิดข้อผิดพลาดในการบันทึก";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>แยกชื่อ-สกุล</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Inter:wght@400;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Noto Sans Thai', 'Inter', sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.wrapper {
    width: 100%;
    max-width: 900px;
}

.header-section {
    text-align: center;
    color: white;
    margin-bottom: 40px;
    animation: slideDown 0.6s ease-out;
}

.header-section h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 12px;
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.header-section p {
    font-size: 16px;
    opacity: 0.95;
    font-weight: 300;
}

.card {
    background: white;
    border-radius: 30px;
    padding: 50px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.6s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.student-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 18px 24px;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    margin-bottom: 40px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    display: flex;
    align-items: center;
    gap: 12px;
}

.student-info::before {
    content: "👤";
    font-size: 24px;
}

.form-section {
    margin-bottom: 40px;
}

.form-section form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.row {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

label {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.input-group {
    display: flex;
    gap: 12px;
    align-items: stretch;
}

input[type="text"] {
    flex: 1;
    padding: 16px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 14px;
    font-size: 16px;
    background: #f8f9fa;
    color: #333;
    transition: all 0.3s ease;
    font-family: 'Noto Sans Thai', sans-serif;
}

input[type="text"]:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

input[readonly] {
    background: #f0f0f0;
    cursor: not-allowed;
}

button {
    padding: 16px 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 14px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
    white-space: nowrap;
    font-family: 'Noto Sans Thai', sans-serif;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
}

button:active {
    transform: translateY(-1px);
}

.result {
    padding-top: 40px;
    border-top: 2px solid #e0e0e0;
}

.result-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.result-title::before {
    content: "✨";
    font-size: 24px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.grid > div {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
    border-radius: 16px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.grid > div:hover {
    border-color: #667eea;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
    transform: translateY(-5px);
}

.grid > div label {
    display: block;
    margin-bottom: 12px;
    color: #667eea;
}

.grid > div input {
    width: 100%;
    text-align: center;
    font-weight: 600;
    font-size: 18px;
    color: #333;
    border: none !important;
    background: transparent !important;
    padding: 0 !important;
}

@media (max-width: 768px) {
    .card {
        padding: 30px 20px;
    }

    .header-section h1 {
        font-size: 32px;
    }

    .input-group {
        flex-direction: column;
    }

    .grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

.success-message {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 16px 20px;
    border-radius: 14px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
    animation: slideDown 0.4s ease-out;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
}
</style>
</head>

<body>
<div class="wrapper">
    <div class="header-section">
        <h1>🎯 แยกชื่อ-สกุล</h1>
        <p>กรอกชื่อ-สกุลเต็ม เพื่อแยกข้อมูลอัตโนมัติ</p>
    </div>

    <div class="card">
        <div class="student-info">
            รหัสนักศึกษา: <?= htmlspecialchars($studentId, ENT_QUOTES, 'UTF-8') ?>
        </div>

        <div class="form-section">
            <form method="post">
                <div class="row">
                    <label>ชื่อ-สกุล</label>
                    <div class="input-group">
                        <input type="text" name="fullname" placeholder="เช่น นาย นาวี สุชาติ"
                        value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname'],ENT_QUOTES,'UTF-8') : '' ?>" required>
                        <button type="submit">⚡ แยกข้อมูล</button>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($message): ?>
        <div class="success-message">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <div class="result">
            <div class="result-title">ผลลัพธ์</div>
            <div class="grid">
                <div>
                    <label>👑 คำนำหน้า</label>
                    <input type="text" readonly value="<?= htmlspecialchars($data['prefix'],ENT_QUOTES,'UTF-8') ?>">
                </div>
                <div>
                    <label>📝 ชื่อ</label>
                    <input type="text" readonly value="<?= htmlspecialchars($data['firstname'],ENT_QUOTES,'UTF-8') ?>">
                </div>
                <div>
                    <label>🔖 สกุล</label>
                    <input type="text" readonly value="<?= htmlspecialchars($data['lastname'],ENT_QUOTES,'UTF-8') ?>">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>