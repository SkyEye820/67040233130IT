<?php
require_once 'db.php';

$edit_mode = false;
$edit_data = null;

// ลบข้อมูล
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM registrations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $success_message = "ลบข้อมูลสำเร็จ";
    } else {
        $error_message = "เกิดข้อผิดพลาดในการลบ: " . $stmt->error;
    }
    $stmt->close();
    header("Location: index.php");
    exit;
}

// แก้ไขข้อมูล - โหลดข้อมูลเดิม
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $sql = "SELECT * FROM registrations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_data = $result->fetch_assoc();
    $stmt->close();
    
    if ($edit_data) {
        $edit_mode = true;
    }
}

// บันทึกหรืออัพเดตข้อมูล
if (isset($_POST['submit'])) {

    $fullname = htmlspecialchars($_POST['fullname']);
    $email    = htmlspecialchars($_POST['email']);
    $course   = htmlspecialchars($_POST['course']);
    $type     = htmlspecialchars($_POST['type']);

    if (isset($_POST['food'])) {
        $food = implode(",", $_POST['food']);
    } else {
        $food = "ไม่ระบุ";
    }

    if ($type == "Onsite") {
        $price = 1500;
    } else {
        $price = 800;
    }

    // แก้ไขข้อมูล
    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        $edit_id = intval($_POST['edit_id']);
        $sql = "UPDATE registrations SET fullname = ?, email = ?, course = ?, food = ?, type = ?, price = ? WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("sssssii", $fullname, $email, $course, $food, $type, $price, $edit_id);
        
        if ($stmt->execute()) {
            $success_message = "อัพเดตข้อมูลสำเร็จ";
            $edit_mode = false;
        } else {
            $error_message = "เกิดข้อผิดพลาด: " . $stmt->error;
        }
    } else {
        // บันทึกข้อมูลใหม่
        $sql = "INSERT INTO registrations (fullname, email, course, food, type, price) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("sssssi", $fullname, $email, $course, $food, $type, $price);
        
        if ($stmt->execute()) {
            $success_message = "ลงทะเบียนสำเร็จ";
        } else {
            $error_message = "เกิดข้อผิดพลาด: " . $stmt->error;
        }
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ฟอร์มลงทะเบียนอบรม</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
* {
    font-family: 'Prompt', sans-serif;
}

body {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    padding: 20px 0;
}

.container {
    max-width: 900px;
}

/* ฟอร์มหลัก */
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: slideUp 0.5s ease-out;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
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

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 20px 20px 0 0;
    padding: 35px 20px;
    text-align: center;
}

.card-header h4 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.card-body {
    padding: 40px;
    background: linear-gradient(135deg, #f5f7ff 0%, #ffffff 100%);
}

/* Form Elements */
.form-label {
    color: #333;
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-control,
.form-select {
    border: 2px solid #e0e7ff;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: 15px;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), 0 4px 15px rgba(0, 0, 0, 0.08);
    outline: none;
}

.form-control::placeholder {
    color: #b0b9d4;
}

.form-check {
    padding: 12px 16px;
    background: white;
    border-radius: 10px;
    border: 2px solid #e0e7ff;
    margin-bottom: 12px;
    transition: all 0.3s ease;
}

.form-check:hover {
    border-color: #667eea;
    background: #f5f7ff;
}

.form-check-input {
    width: 20px;
    height: 20px;
    accent-color: #667eea;
    cursor: pointer;
    border: 2px solid #d0d8f0;
    border-radius: 6px;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    cursor: pointer;
    user-select: none;
    color: #333;
    font-weight: 500;
    margin-left: 8px;
}

/* ปุ่ม */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 14px 28px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-primary:active {
    transform: translateY(-1px);
}

.btn-secondary {
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    border: none;
    border-radius: 12px;
    padding: 14px 28px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
}

.btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(255, 107, 107, 0.4);
    color: white;
}

.btn-group-form {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}

/* Alert Messages */
.alert {
    border: none;
    border-radius: 15px;
    padding: 20px;
    animation: slideDown 0.4s ease-out;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 5px solid #28a745;
    color: #155724;
}

.alert-success h5 {
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left: 5px solid #dc3545;
    color: #721c24;
}

.alert-danger h5 {
    color: #721c24;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border-left: 5px solid #17a2b8;
    color: #0c5460;
}

/* ตาราง */
.table-card {
    margin-top: 40px;
}

.table-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    padding: 20px !important;
}

.table {
    margin: 0;
    font-size: 14px;
}

.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white;
    border: none !important;
    font-weight: 600;
    padding: 15px 10px !important;
    text-align: center;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 15px 10px;
    border-color: #e0e7ff;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e0e7ff;
}

.table tbody tr:hover {
    background-color: #f5f7ff;
    box-shadow: inset 0 0 10px rgba(102, 126, 234, 0.1);
}

/* ปุ่มในตาราง */
.btn-sm {
    padding: 8px 12px;
    font-size: 12px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: white;
}

.btn-warning:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    color: white;
}

.btn-danger:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
}

/* Responsive */
@media (max-width: 576px) {
    .card-body {
        padding: 25px 20px;
    }
    
    .card-header h4 {
        font-size: 22px;
    }
    
    .btn-group-form {
        flex-direction: column;
    }
    
    .btn-group-form .btn {
        width: 100%;
    }
    
    .table {
        font-size: 12px;
    }
    
    .table thead th {
        padding: 10px 5px !important;
        font-size: 11px;
    }
    
    .table tbody td {
        padding: 10px 5px;
    }
}
</style>
</head>

<body>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-12">

<!-- ฟอร์ม -->
<div class="card shadow-lg mb-4">
<div class="card-header">
    <h4>
        <?php if ($edit_mode) { 
            echo '<i class="fas fa-edit"></i> แก้ไขข้อมูลลงทะเบียน'; 
        } else { 
            echo '<i class="fas fa-clipboard-list"></i> ฟอร์มลงทะเบียนอบรม'; 
        } ?>
    </h4>
</div>

<div class="card-body">

<form method="post">

<?php if ($edit_mode) { ?>
    <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
<?php } ?>

<div class="row">
    <div class="col-md-6 mb-4">
        <label class="form-label">
            <i class="fas fa-user" style="color: #667eea;"></i> ชื่อ-นามสกุล
        </label>
        <input type="text" name="fullname" class="form-control" placeholder="กรุณาระบุชื่อ-นามสกุล" value="<?= $edit_mode ? htmlspecialchars($edit_data['fullname']) : '' ?>" required>
    </div>

    <div class="col-md-6 mb-4">
        <label class="form-label">
            <i class="fas fa-envelope" style="color: #667eea;"></i> Email
        </label>
        <input type="email" name="email" class="form-control" placeholder="ตัวอย่าง: your@email.com" value="<?= $edit_mode ? htmlspecialchars($edit_data['email']) : '' ?>" required>
    </div>
</div>

<div class="mb-4">
    <label class="form-label">
        <i class="fas fa-book" style="color: #667eea;"></i> หัวข้ออบรม
    </label>
    <select name="course" class="form-select" required>
        <option value="">-- เลือกหัวข้ออบรม --</option>
        <option value="AI สำหรับงานสำนักงาน" <?= ($edit_mode && $edit_data['course'] == 'AI สำหรับงานสำนักงาน') ? 'selected' : '' ?>>🤖 AI สำหรับงานสำนักงาน</option>
        <option value="Excel สำหรับการทำงาน" <?= ($edit_mode && $edit_data['course'] == 'Excel สำหรับการทำงาน') ? 'selected' : '' ?>>📊 Excel สำหรับการทำงาน</option>
        <option value="การเขียนเว็บด้วย PHP" <?= ($edit_mode && $edit_data['course'] == 'การเขียนเว็บด้วย PHP') ? 'selected' : '' ?>>💻 การเขียนเว็บด้วย PHP</option>
    </select>
</div>

<div class="mb-4">
    <label class="form-label">
        <i class="fas fa-utensils" style="color: #667eea;"></i> อาหารที่ต้องการ
    </label>
    <div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="food[]" value="ปกติ" id="food1"
                <?= ($edit_mode && strpos($edit_data['food'], 'ปกติ') !== false) ? 'checked' : '' ?>>
            <label class="form-check-label" for="food1">
                <span style="font-weight: 600;">ปกติ</span> - อาหารทั่วไป
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="food[]" value="มังสวิรัติ" id="food2"
                <?= ($edit_mode && strpos($edit_data['food'], 'มังสวิรัติ') !== false) ? 'checked' : '' ?>>
            <label class="form-check-label" for="food2">
                <span style="font-weight: 600;">มังสวิรัติ</span> - ไม่มีเนื้อสัตว์
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="food[]" value="ฮาลาล" id="food3"
                <?= ($edit_mode && strpos($edit_data['food'], 'ฮาลาล') !== false) ? 'checked' : '' ?>>
            <label class="form-check-label" for="food3">
                <span style="font-weight: 600;">ฮาลาล</span> - อาหารอิสลาม
            </label>
        </div>
    </div>
</div>

<div class="mb-5">
    <label class="form-label">
        <i class="fas fa-map-marker-alt" style="color: #667eea;"></i> รูปแบบการเข้าร่วม
    </label>
    <div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="type" value="Onsite" id="type1"
                <?= ($edit_mode && $edit_data['type'] == 'Onsite') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="type1">
                <span style="font-weight: 600;">📍 Onsite</span> - ที่สถานที่ (1,500 บาท)
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="type" value="Online" id="type2"
                <?= ($edit_mode && $edit_data['type'] == 'Online') ? 'checked' : '' ?>>
            <label class="form-check-label" for="type2">
                <span style="font-weight: 600;">🌐 Online</span> - ออนไลน์ผ่าน Zoom (800 บาท)
            </label>
        </div>
    </div>
</div>

<div class="btn-group-form">
    <button type="submit" name="submit" class="btn btn-primary btn-lg flex-grow-1">
        <i class="fas fa-check-circle"></i>
        <?php if ($edit_mode) { echo "อัพเดตข้อมูล"; } else { echo "ลงทะเบียน"; } ?>
    </button>
    <?php if ($edit_mode) { ?>
    <a href="index.php" class="btn btn-secondary btn-lg flex-grow-1">
        <i class="fas fa-times-circle"></i> ยกเลิก
    </a>
    <?php } ?>
</div>

</form>
</div>
</div>

<!-- แสดงผลข้อความสำเร็จ -->
<?php if (isset($success_message)) { ?>
<div class="alert alert-success shadow-lg">
    <div style="display: flex; align-items: center; gap: 15px;">
        <i class="fas fa-check-circle" style="font-size: 28px;"></i>
        <div>
            <h5 class="fw-bold mb-2">✅ <?= $edit_mode ? 'อัพเดตข้อมูลสำเร็จ' : 'ลงทะเบียนสำเร็จ' ?></h5>
            <div style="font-size: 14px; line-height: 1.8;">
                <strong>ชื่อ:</strong> <?= $fullname ?><br>
                <strong>Email:</strong> <?= $email ?><br>
                <strong>หัวข้อ:</strong> <?= $course ?><br>
                <strong>อาหาร:</strong> <?= $food ?><br>
                <strong>รูปแบบ:</strong> <?= $type ?><br>
                <strong>ค่าลงทะเบียน:</strong> <span style="color: #28a745; font-size: 16px; font-weight: 700;">฿ <?= number_format($price, 2) ?></span>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if (isset($error_message)) { ?>
<div class="alert alert-danger shadow-lg">
    <h5 class="fw-bold mb-2">❌ เกิดข้อผิดพลาด</h5>
    <p class="mb-0"><?= $error_message ?></p>
</div>
<?php } ?>

<!-- ตาราง -->
<?php
$sql = "SELECT id, fullname, email, course, food, type, price, DATE_FORMAT(created_at, '%d/%m/%Y %H:%i') as created_at FROM registrations ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<div class='card shadow-lg table-card'>";
    echo "<div class='card-header table-header'>";
    echo "<h5 class='m-0 fw-bold text-white'>";
    echo "<i class='fas fa-list-ul'></i> รายชื่อผู้ลงทะเบียนทั้งหมด ";
    echo "<span style='background: white; color: #f5576c; border-radius: 50%; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; margin-left: 12px; font-weight: 700;'>" . $result->num_rows . "</span>";
    echo "</h5>";
    echo "</div>";
    echo "<div class='card-body p-0'>";
    echo "<div style='overflow-x: auto;'>";
    echo "<table class='table table-striped'>";
    echo "<thead>
            <tr>
                <th style='width: 50px;'>#</th>
                <th><i class='fas fa-user'></i><br>ชื่อ</th>
                <th><i class='fas fa-envelope'></i><br>Email</th>
                <th><i class='fas fa-book'></i><br>หัวข้อ</th>
                <th><i class='fas fa-utensils'></i><br>อาหาร</th>
                <th><i class='fas fa-map-marker-alt'></i><br>รูปแบบ</th>
                <th><i class='fas fa-dollar-sign'></i><br>ราคา</th>
                <th><i class='fas fa-calendar'></i><br>วันเวลา</th>
                <th style='width: 150px;'><i class='fas fa-tools'></i><br>จัดการ</th>
            </tr>
          </thead><tbody>";

    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        $typeIcon = $row['type'] == 'Onsite' ? '📍' : '🌐';
        echo "<tr>
                <td style='font-weight: bold; color: #667eea;'>" . str_pad($counter, 2, '0', STR_PAD_LEFT) . "</td>
                <td><strong>{$row['fullname']}</strong></td>
                <td><small>{$row['email']}</small></td>
                <td><small>{$row['course']}</small></td>
                <td><small>{$row['food']}</small></td>
                <td><span>" . $typeIcon . " {$row['type']}</span></td>
                <td><strong style='color: #667eea;'>฿ " . number_format($row['price'], 2) . "</strong></td>
                <td><small>{$row['created_at']}</small></td>
                <td style='text-align: center;'>
                    <a href='index.php?edit_id={$row['id']}' class='btn btn-warning btn-sm' title='แก้ไข'>
                        <i class='fas fa-edit'></i>
                    </a>
                    <a href='index.php?delete_id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('คุณแน่ใจหรือว่าต้องการลบข้อมูลของ {$row['fullname']} ?')\" title='ลบ'>
                        <i class='fas fa-trash'></i>
                    </a>
                </td>
              </tr>";
        $counter++;
    }
    echo "</tbody></table></div></div></div>";
} else {
    echo "<div class='alert alert-info shadow-lg mt-4' style='border: none; border-left: 5px solid #17a2b8;'>";
    echo "<h5 class='fw-bold mb-2'><i class='fas fa-info-circle'></i> ยังไม่มีข้อมูลลงทะเบียน</h5>";
    echo "<p class='mb-0'>เมื่อมีการลงทะเบียน ข้อมูลจะแสดงในตารางนี้</p>";
    echo "</div>";
}

$conn->close();
?>

</div>
</div>
</div>

</body>
</html>
