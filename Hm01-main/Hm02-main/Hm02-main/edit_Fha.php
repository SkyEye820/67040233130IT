<?php
require_once 'db_Fha.php';
require_once 'functions_Fha.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$record = null;
$error = '';
$success = '';

// ดึงข้อมูล
if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM bmi_records WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $record = $stmt->fetch();
    } catch (PDOException $e) {
        $error = 'ไม่สามารถดึงข้อมูลได้: ' . $e->getMessage();
    }
}

if (!$record) {
    header('Location: records_Fha.php?error=' . urlencode('ไม่พบข้อมูล'));
    exit;
}

// ประมวลผลการส่ง form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullName'] ?? '');
    $weight = (float)($_POST['weight'] ?? 0);
    $height = (float)($_POST['height'] ?? 0);
    
    if (!$fullname || $weight <= 0 || $height <= 0) {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง';
    } else {
        try {
            $height_m = $height / 100;
            $bmi = round($weight / ($height_m * $height_m), 2);
            $classify = classifyBMI($bmi);
            
            $stmt = $pdo->prepare("
                UPDATE bmi_records 
                SET fullname = :fullname, weight = :weight, height = :height, bmi = :bmi
                WHERE id = :id
            ");
            $stmt->execute([
                ':fullname' => $fullname,
                ':weight' => $weight,
                ':height' => $height,
                ':bmi' => $bmi,
                ':id' => $id
            ]);
            
            $success = 'อัพเดทข้อมูลสำเร็จ';
            
            // อัพเดทข้อมูล record
            $record['fullname'] = $fullname;
            $record['weight'] = $weight;
            $record['height'] = $height;
            $record['bmi'] = $bmi;
        } catch (PDOException $e) {
            $error = 'อัพเดทข้อมูลไม่สำเร็จ: ' . $e->getMessage();
        }
    }
}

$dob = isset($record['created_at']) ? substr($record['created_at'], 0, 10) : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>แก้ไขข้อมูล | HealthCheck Thai</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  
  @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  
  body {
    font-family: 'Sarabun', sans-serif;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
    color: #1e293b;
    min-height: 100vh;
    padding-top: 70px;
    animation: fadeIn 0.6s ease-out;
  }

  nav {
    background: linear-gradient(90deg, #1e293b 0%, #3b82f6 50%, #6366f1 100%);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    padding: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 999;
    border-bottom: 3px solid #60a5fa;
    animation: slideDown 0.5s ease-out;
  }

  nav > div {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
  }

  nav .logo {
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
    font-weight: 800;
    font-size: 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
  }

  nav .logo:hover {
    transform: scale(1.08) translateX(5px);
    text-shadow: 0 0 20px rgba(96,165,250,0.5);
  }

  nav .logo svg {
    width: 32px;
    height: 32px;
    stroke: #60a5fa;
    filter: drop-shadow(0 0 8px rgba(96,165,250,0.3));
  }

  nav .nav-links {
    display: flex;
    gap: 30px;
    align-items: center;
    list-style: none;
  }

  nav .nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 10px 18px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  nav .nav-links a:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(96,165,250,0.3);
  }

  nav .nav-links a.active {
    background: #60a5fa;
    color: white;
    box-shadow: 0 4px 16px rgba(96,165,250,0.4);
  }

  header {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(240, 244, 255, 0.95) 100%);
    padding: 60px 30px;
    text-align: center;
    border-radius: 0 0 30px 30px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
    margin-bottom: 50px;
    backdrop-filter: blur(10px);
    animation: slideDown 0.6s ease-out;
  }

  header h1 {
    font-size: 48px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 15px;
    font-weight: 900;
    letter-spacing: -0.5px;
  }

  header p {
    font-size: 18px;
    color: #64748b;
    margin: 0;
    font-weight: 500;
  }

  main {
    max-width: 700px;
    margin: 0 auto;
    padding: 0 20px;
    margin-bottom: 60px;
  }

  section {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    animation: fadeIn 0.6s ease-out;
    border: 1px solid rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
  }

  .success-box {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    border-left: 5px solid #16a34a;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 30px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    animation: slideDown 0.4s ease-out;
  }

  .success-box svg {
    width: 28px;
    height: 28px;
    color: #16a34a;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .success-box p {
    color: #166534;
    font-weight: 700;
    margin: 0;
    font-size: 15px;
  }

  .success-box svg {
    width: 24px;
    height: 24px;
    color: #16a34a;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .success-box p {
    color: #166534;
    font-weight: 500;
    margin: 0;
  }

  .error-box {
    background: #fee2e2;
    border-left: 5px solid #dc2626;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
  }

  .error-box svg {
    width: 24px;
    height: 24px;
    color: #dc2626;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .error-box p {
    color: #991b1b;
    font-weight: 500;
    margin: 0;
  }

  form > div {
    margin-bottom: 25px;
  }

  form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  label {
    display: block;
    color: #1e3a8a;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 14px;
  }

  input[type="text"],
  input[type="number"],
  input[type="date"],
  textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-family: 'Sarabun', sans-serif;
    font-size: 16px;
    transition: all 0.3s;
  }

  input:focus,
  textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 8px rgba(102, 126, 234, 0.2);
  }

  textarea {
    resize: vertical;
    min-height: 100px;
  }

  .button-group {
    display: flex;
    gap: 12px;
    margin-top: 30px;
  }

  button {
    flex: 1;
    padding: 14px;
    border: none;
    border-radius: 8px;
    font-family: 'Sarabun', sans-serif;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  .btn-save {
    background: #16a34a;
    color: white;
  }

  .btn-save:hover {
    background: #15803d;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(22, 163, 74, 0.3);
  }

  .btn-cancel {
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #cbd5e1;
  }

  .btn-cancel:hover {
    background: #e2e8f0;
  }

  a.btn-cancel {
    text-decoration: none;
  }

  .info-box {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .info-box p {
    color: #1e40af;
    margin: 0;
    font-size: 14px;
  }

  footer {
    background: rgba(30, 58, 138, 0.95);
    color: white;
    padding: 40px 30px;
    margin-top: 60px;
    text-align: center;
  }

  footer p {
    margin: 0;
    opacity: 0.9;
  }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div>
    <a href="index_Fha.php" class="logo">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
      </svg>
      HealthCheck Thai
    </a>
    <ul class="nav-links">
      <li><a href="index_Fha.php"><i class="fas fa-home"></i> หัวหน้า</a></li>
      <li><a href="records_Fha.php" class="active"><i class="fas fa-list"></i> ข้อมูล</a></li>
    </ul>
  </div>
</nav>

<!-- HEADER -->
<header>
  <h1>แก้ไขข้อมูล BMI</h1>
  <p>อัพเดทข้อมูลการประเมิน BMI</p>
</header>

<!-- MAIN -->
<main>

  <section>
    <?php if ($success): ?>
      <div class="success-box">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
        </svg>
        <p><?= htmlspecialchars($success) ?></p>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="error-box">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
        </svg>
        <p><?= htmlspecialchars($error) ?></p>
      </div>
    <?php endif; ?>

    <div class="info-box">
      <p><strong>ข้อมูลปัจจุบัน:</strong> รหัส #<?= $record['id'] ?> | ชื่อ: <?= htmlspecialchars($record['fullname']) ?></p>
    </div>

    <form method="POST">
      <div>
        <label for="fullName">ชื่อ-นามสกุล</label>
        <input 
          type="text" 
          id="fullName" 
          name="fullName" 
          value="<?= htmlspecialchars($record['fullname']) ?>"
          required
        />
      </div>

      <div class="form-row">
        <div>
          <label for="height">ส่วนสูง (ซม.)</label>
          <input 
            type="number" 
            id="height" 
            name="height" 
            step="0.1"
            min="50"
            max="300"
            value="<?= $record['height'] ?>"
            required
          />
        </div>
        <div>
          <label for="weight">น้ำหนัก (กก.)</label>
          <input 
            type="number" 
            id="weight" 
            name="weight" 
            step="0.1"
            min="10"
            max="300"
            value="<?= $record['weight'] ?>"
            required
          />
        </div>
      </div>

      <div class="form-row">
        <div>
          <label for="dob">วันเกิด</label>
          <input 
            type="date" 
            id="dob" 
            name="dob" 
            value="<?= $dob ?>"
            disabled
          />
        </div>
        <div>
          <label>BMI ปัจจุบัน</label>
          <input 
            type="text" 
            value="<?= number_format($record['bmi'], 2) ?>"
            disabled
          />
        </div>
      </div>

      <div class="button-group">
        <button type="submit" class="btn-save">
          <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
        </button>
        <a href="records_Fha.php" class="btn-cancel" style="display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;">
          <i class="fas fa-times"></i> ยกเลิก
        </a>
      </div>
    </form>
  </section>

</main>

<!-- FOOTER -->
<footer>
  <p>© <?= date('Y') ?> ระบบบริหารจัดการสุขภาพดิจิทัล. สงวนลิขสิทธิ์.</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
