<?php
/**
 * โปรแกรมคำนวณส่วนลด
 * ชื่อ: นนทวัฒน์ นาเวียง
 * รหัสนักศึกษา: 67040233130
 */

// เชื่อมต่อกับ Database
require_once 'config.php';
global $conn;

$amountRaw = isset($_POST['amount']) ? $_POST['amount'] : '';
$amount = is_numeric($amountRaw) ? floatval($amountRaw) : 0;
$isMember = isset($_POST['member']);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_numeric($amountRaw) || $amount < 0) {
        $error = 'ยอดซื้อไม่สามารถเป็นค่าที่ไม่ถูกต้องหรือติดลบได้';
    }
}

$discountRate = 0;
$level = 'ไม่ได้รับส่วนลด';
$nextTarget = null;
$nextRate = null;

if ($amount >= 5000) {
    $discountRate = 20;
    $level = 'Platinum';
} elseif ($amount >= 3000) {
    $discountRate = 15;
    $level = 'Gold';
    $nextTarget = 5000;
    $nextRate = 20;
} elseif ($amount >= 1000) {
    $discountRate = 10;
    $level = 'Silver';
    $nextTarget = 3000;
    $nextRate = 15;
} elseif ($amount >= 500) {
    $discountRate = 5;
    $level = 'Bronze';
    $nextTarget = 1000;
    $nextRate = 10;
} else {
    $nextTarget = 500;
    $nextRate = 5;
}

$memberRate = ($isMember && $amount >= 500) ? 5 : 0;
$totalDiscountRate = $discountRate + $memberRate;
$discountMoney = $amount * ($totalDiscountRate / 100);
$netPrice = $amount - $discountMoney;

// บันทึกข้อมูลลง Database ถ้าไม่มี error
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $isMemberValue = $isMember ? 1 : 0;
    
    $stmt = $conn->prepare("INSERT INTO orders (amount, discount_rate, member_rate, total_discount_rate, discount_money, net_price, is_member, level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("diiiidis", $amount, $discountRate, $memberRate, $totalDiscountRate, $discountMoney, $netPrice, $isMemberValue, $level);
        $stmt->execute();
        $stmt->close();
        $success = 'บันทึกข้อมูลเรียบร้อยแล้ว';
    }
}
?>

<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>คำนวณส่วนลด</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;600;700&display=swap');

:root{
    --primary:#7c3aed;
    --secondary:#8b5cf6;
    --danger:#dc2626;
    --text:#1f1f2e;
    --muted:#4b5563;
    --glass:rgba(20,20,35,.85);
}

body{
    font-family:'Noto Sans Thai',sans-serif;
    min-height:100vh;
    margin:0;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#2d1b4e,#1a1a2e);
}

.container{
    width:100%;
    max-width:760px;
    padding:20px;
}

.card{
    background:var(--glass);
    backdrop-filter: blur(12px);
    border-radius:20px;
    padding:28px;
    box-shadow:0 20px 40px rgba(0,0,0,.12);
}

h1{
    margin:0;
    font-size:26px;
    font-weight:700;
    color:#e5e5e5;
}

.subtitle{
    margin-top:6px;
    color:#a8a9c1;
    font-size:14px;
}

.form-box{
    margin-top:24px;
    padding:20px;
    border-radius:16px;
    background:#2a2a3e;
    border:1px solid #4a4a6a;
}

.row{
    display:flex;
    gap:12px;
    align-items:center;
    margin-bottom:16px;
}

input[type="number"]{
    flex:1;
    padding:12px;
    border-radius:12px;
    border:1px solid #4a4a6a;
    font-size:15px;
    background:#1f1f2e;
    color:#e5e5e5;
}

input[type="number"]::placeholder{
    color:#8b5cf6;
}

input[type="checkbox"]{
    accent-color:#7c3aed;
}

label{
    font-size:14px;
    color:#a8a9c1;
}

.actions{
    display:flex;
    align-items:center;
    gap:12px;
}

button{
    background:linear-gradient(135deg,#7c3aed,#a855f7);
    color:#fff;
    border:none;
    padding:12px 22px;
    border-radius:999px;
    font-size:15px;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(124,58,237,.45);
    transition:.2s;
}

button:hover{
    transform:translateY(-2px);
}

.error{
    background:#5f2f3e;
    color:#ff8a8a;
    padding:12px;
    border-radius:12px;
    margin-top:16px;
    border-left:3px solid #dc2626;
}

.success{
    background:#2d4a3f;
    color:#7ee8a8;
    padding:12px;
    border-radius:12px;
    margin-top:16px;
    border-left:3px solid #10b981;
}

.result{
    margin-top:24px;
    background:#2a2a3e;
    border-radius:16px;
    padding:20px;
    border:1px solid #4a4a6a;
}

.result-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
    color:#e5e5e5;
}

.badge{
    padding:6px 14px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
    background:#4a2c6b;
    color:#d8b4ff;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    padding:10px 0;
    border-bottom:1px dashed #4a4a6a;
    color:#e5e5e5;
}

.right{text-align:right}

.total{
    font-size:18px;
    color:#a78bfa;
    font-weight:700;
}

.note{
    margin-top:12px;
    color:#a8a9c1;
    font-size:14px;
}
</style>
</head>

<body>
<div class="container">
<div class="card">

<h1>โปรแกรมคำนวณส่วนลด</h1>
<p class="subtitle">คำนวณราคาสุทธิพร้อมแสดงระดับส่วนลด</p>

<?php if ($error): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="form-box">
<form method="post">
    <div class="row">
        <input type="number" name="amount" step="0.01"
        placeholder="ยอดซื้อ (บาท)"
        value="<?= htmlspecialchars($amountRaw) ?>" required>
        <label>
            <input type="checkbox" name="member" <?= $isMember ? 'checked':'' ?>>
            เป็นสมาชิก
        </label>
    </div>
    <div class="actions">
        <button type="submit">คำนวณราคา</button>
        <span class="note">สมาชิก +5% เมื่อซื้อ ≥ 500 บาท</span>
    </div>
</form>
</div>

<?php if ($_SERVER['REQUEST_METHOD']==='POST' && !$error): ?>
<div class="result">
    <div class="result-header">
        <strong>สรุปผลการคำนวณ</strong>
        <span class="badge"><?= $level ?></span>
    </div>

    <table>
        <tr>
            <td>ยอดซื้อ</td>
            <td class="right"><?= number_format($amount,2) ?> บาท</td>
        </tr>
        <tr>
            <td>ส่วนลดระดับ</td>
            <td class="right"><?= $discountRate ? $discountRate.'%' : '–' ?></td>
        </tr>
        <tr>
            <td>ส่วนลดสมาชิก</td>
            <td class="right"><?= $memberRate ? '+'.$memberRate.'%' : '–' ?></td>
        </tr>
        <tr>
            <td>รวมส่วนลด</td>
            <td class="right"><?= $totalDiscountRate ?>%</td>
        </tr>
        <tr>
            <td>จำนวนเงินที่ลด</td>
            <td class="right"><?= number_format($discountMoney,2) ?> บาท</td>
        </tr>
        <tr>
            <td><strong>ราคาสุทธิ</strong></td>
            <td class="right total"><?= number_format($netPrice,2) ?> บาท</td>
        </tr>
    </table>

    <?php if ($nextTarget !== null && $amount < $nextTarget): ?>
        <p class="note">
            ซื้อเพิ่มอีก <strong><?= number_format($nextTarget-$amount,2) ?> บาท</strong>
            เพื่อรับส่วนลด <?= $nextRate ?>%
        </p>
    <?php endif; ?>
</div>
<?php endif; ?>

</div>
</div>
</body>
</html>
