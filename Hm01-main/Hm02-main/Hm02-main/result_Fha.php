<?php
require_once 'db_Fha.php';
require_once 'functions_Fha.php';

// ==========================================
// ตรวจสอบว่ามาจาก POST เท่านั้น
// ==========================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index_Fha.php');
    exit;
}

$fullname = trim($_POST['fullName'] ?? '');
$dob      = trim($_POST['dob']      ?? '');
$weight   = (float)($_POST['weight'] ?? 0);
$height   = (float)($_POST['height'] ?? 0);

if (!$fullname || !$dob || $weight <= 0 || $height <= 0) {
    header('Location: index_Fha.php?error=' . urlencode('กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง'));
    exit;
}

// คำนวณ
$age      = calculateAge($dob);
$height_m = $height / 100;
$bmi      = round($weight / ($height_m * $height_m), 2);
$classify = classifyBMI($bmi);

// บันทึกลง DB
try {
    $stmt = $pdo->prepare("
        INSERT INTO bmi_records (fullname, dob, age, weight, height, bmi, created_at, updated_at)
        VALUES (:fullname, :dob, :age, :weight, :height, :bmi, NOW(), NOW())
    ");
    $stmt->execute([
        ':fullname' => $fullname,
        ':dob'      => $dob,
        ':age'      => $age,
        ':weight'   => $weight,
        ':height'   => $height,
        ':bmi'      => $bmi,
    ]);
    $record_id = $pdo->lastInsertId();
} catch (PDOException $e) {
    header('Location: index_Fha.php?error=' . urlencode('บันทึกข้อมูลไม่สำเร็จ: ' . $e->getMessage()));
    exit;
}

// น้ำหนักเป้าหมาย (BMI 18.5–22.9)
$ideal_min = round(18.5 * $height_m * $height_m, 1);
$ideal_max = round(22.9 * $height_m * $height_m, 1);
$c = $classify;
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>ผลลัพธ์ BMI | HealthCheck Thai</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<style>
  /* ===== ตั้งค่าพื้นฐาน ===== */
  * { box-sizing: border-box; margin: 0; padding: 0; }
  
  @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes slideInLeft { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.8; } }
  
  body {
    font-family: 'Sarabun', sans-serif;
    background: linear-gradient(135deg, <?= $c['color'] ?> 0%, <?= $c['border'] ?> 100%);
    color: #1e293b;
    min-height: 100vh;
    padding-top: 70px;
    animation: fadeIn 0.6s ease-out;
  }

  /* ===== NAVBAR ===== */
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

  nav .record-id {
    background: rgba(255, 255, 255, 0.15);
    padding: 10px 20px;
    border-radius: 20px;
    color: white;
    font-weight: 700;
    font-size: 14px;
    backdrop-filter: blur(10px);
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

  /* ===== HERO SECTION ===== */
  header {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(240, 244, 255, 0.95) 100%);
    padding: 50px 30px;
    text-align: center;
    border-radius: 0 0 30px 30px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
    margin-bottom: 50px;
    backdrop-filter: blur(10px);
    animation: slideDown 0.6s ease-out;
  }

  header p {
    color: #64748b;
    font-size: 15px;
    margin-bottom: 15px;
    font-weight: 500;
  }

  header h1 {
    font-size: 44px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
    font-weight: 900;
    letter-spacing: -0.5px;
  }

  /* ===== MAIN LAYOUT ===== */
  main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    margin-bottom: 60px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: stretch;
  }

  /* ===== BMI HERO CARD ===== */
  .bmi-hero {
    background: white;
    border-radius: 24px;
    padding: 45px;
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    animation: slideUp 0.6s ease-out;
    position: relative;
    grid-column: 2;
    grid-row: 1;
  }

  .bmi-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 8px;
    background: linear-gradient(90deg, <?= $c['color'] ?>, <?= $c['border'] ?>);
  }

  .bmi-hero .content {
    display: flex;
    flex-direction: column;
    gap: 30px;
    align-items: center;
  }

  .bmi-hero .bmi-circle {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: linear-gradient(135deg, <?= $c['bg'] ?> 0%, <?= $c['color'] ?>15 100%);
    border: 5px solid <?= $c['color'] ?>;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1), inset 0 0 20px rgba(255,255,255,0.5);
    animation: slideInLeft 0.6s ease-out 0.2s both;
  }

  .bmi-hero .bmi-value {
    font-size: 56px;
    font-weight: 900;
    color: <?= $c['color'] ?>;
    line-height: 1;
  }

  .bmi-hero .bmi-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
    margin-top: 6px;
  }

  .bmi-hero .info {
    animation: slideInLeft 0.6s ease-out 0.3s both;
  }

  .bmi-hero .status {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
  }

  .bmi-hero .icon {
    font-size: 40px;
  }

  .bmi-hero .status-info h2 {
    font-size: 32px;
    color: #1e3a8a;
    font-weight: 900;
    margin: 0;
    margin-bottom: 6px;
  }

  .bmi-hero .status-info p {
    font-size: 14px;
    color: #64748b;
    margin: 0;
    font-weight: 500;
  }

  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #f1f5f9;
  }

  .info-item {
    display: flex;
    flex-direction: column;
  }

  .info-item label {
    font-size: 12px;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
  }

  .info-item value {
    font-size: 20px;
    font-weight: 800;
    background: linear-gradient(135deg, <?= $c['color'] ?> 0%, <?= $c['border'] ?> 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .bmi-value-circle {
    width: 200px;
    height: 200px;
    background: <?= $c['bg'] ?>;
    border: 6px solid <?= $c['border'] ?>;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .bmi-value-circle p {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 5px;
  }

  .bmi-value-circle .value {
    font-size: 48px;
    font-weight: 800;
    color: <?= $c['color'] ?>;
  }

  .status-info h3 {
    color: <?= $c['color'] ?>;
    font-size: 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .status-info .detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 20px 0;
    padding: 20px 0;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
  }

  .detail-item {
    text-align: center;
  }

  .detail-item p:first-child {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 5px;
  }

  .detail-item p:last-child {
    font-size: 18px;
    font-weight: 700;
    color: <?= $c['color'] ?>;
  }

  .summary-text {
    margin-top: 15px;
    padding: 15px;
    background: <?= $c['bg'] ?>;
    border-left: 4px solid <?= $c['color'] ?>;
    border-radius: 8px;
    color: <?= $c['color'] ?>;
    font-weight: 600;
  }

  /* ===== INFO CARD ===== */
  .info-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    grid-column: 1;
    grid-row: 1;
  }
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  .info-card h2 {
    color: #1e3a8a;
    font-size: 22px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .info-card h2 svg {
    width: 28px;
    height: 28px;
    color: #667eea;
  }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }

  .info-item {
    padding: 15px;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 4px solid #667eea;
  }

  .info-item p:first-child {
    color: #64748b;
    font-size: 13px;
    margin-bottom: 8px;
  }

  .info-item p:last-child {
    font-size: 18px;
    font-weight: 700;
    color: #1e3a8a;
  }

  /* ===== ADVICE CARD ===== */
  .advice-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border-left: 5px solid <?= $c['color'] ?>;
  }

  .advice-card h2 {
    color: <?= $c['color'] ?>;
    font-size: 22px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .advice-card h2 svg {
    width: 28px;
    height: 28px;
  }

  .advice-box {
    background: <?= $c['bg'] ?>;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 15px;
    border-left: 4px solid <?= $c['border'] ?>;
  }

  .advice-box .tip {
    color: <?= $c['color'] ?>;
    font-weight: 700;
    margin-bottom: 10px;
  }

  .advice-box .text {
    color: #475569;
    line-height: 1.6;
  }

  .disclaimer {
    font-size: 12px;
    color: #64748b;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
  }

  /* ===== REFERENCE TABLE ===== */
  .reference-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }

  .reference-card h2 {
    color: #1e3a8a;
    font-size: 22px;
    margin-bottom: 20px;
  }

  .reference-list {
    list-style: none;
  }

  .reference-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    border-left: 5px solid;
    border-radius: 8px;
    background: #f8fafc;
  }

  .reference-list li.active {
    background: rgba(var(--color-rgb), 0.1);
    font-weight: 700;
  }

  .reference-list li .label {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .reference-list li .highlight {
    color: #667eea;
    font-size: 12px;
    background: #667eea;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
  }

  .reference-list li .range {
    font-weight: 700;
  }

  /* ===== BUTTON ===== */
  .back-button {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-top: 10px;
  }

  .back-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
  }

  .back-button svg {
    width: 20px;
    height: 20px;
  }

  /* ===== FOOTER ===== */
  footer {
    background: rgba(30, 58, 138, 0.95);
    color: white;
    padding: 40px 30px;
    margin-top: 60px;
  }

  footer > div {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  footer p {
    margin: 0;
    opacity: 0.9;
  }

  footer .footer-links {
    display: flex;
    gap: 30px;
  }

  footer a {
    color: #93c5fd;
    text-decoration: none;
    transition: 0.3s;
  }

  footer a:hover {
    color: white;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1024px) {
    main {
      grid-template-columns: 1fr;
      gap: 30px;
    }

    .bmi-hero,
    .info-card {
      grid-column: 1;
    }
  }

  @media (max-width: 768px) {
    main {
      grid-template-columns: 1fr;
      gap: 30px;
    }

    .bmi-hero .content {
      flex-direction: column;
    }

    .bmi-hero .bmi-circle {
      width: 150px;
      height: 150px;
    }

    .bmi-hero .bmi-value {
      font-size: 48px;
    }

    .info-grid {
      grid-template-columns: 1fr;
    }

    footer > div {
      flex-direction: column;
      gap: 20px;
      text-align: center;
    }

    footer .footer-links {
      flex-direction: column;
      gap: 10px;
    }
  }

</style>
</head>
<body>

<!-- ============================================================
     NAV — แถบนำทางด้านบน
     ============================================================ -->
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
      <li><a href="records_Fha.php"><i class="fas fa-list"></i> ข้อมูล</a></li>
      <li><span class="record-id">รหัส #<?= $record_id ?></span></li>
    </ul>
  </div>
</nav>

<!-- ============================================================
     PAGE HEADER — หัวหน้าผลลัพธ์
     ============================================================ -->
<header>
  <p>ผลการประเมิน</p>
  <h1>ดัชนีมวลกาย (BMI) ของคุณ</h1>
</header>

<!-- ============================================================
     MAIN — เนื้อหาหลัก
     ============================================================ -->
<main>

  <!-- ===== BMI HERO CARD ===== -->
  <div class="bmi-hero">
    <div class="status-bar"></div>
    <div class="content">
      <div class="bmi-value-circle">
        <p>ค่า BMI</p>
        <div class="value"><?= number_format($bmi, 1) ?></div>
      </div>
      <div class="status-info">
        <h3><?= $c['icon'] ?> <?= htmlspecialchars($c['status']) ?></h3>
        <div class="detail-grid">
          <div class="detail-item">
            <p>น้ำหนักเป้าหมาย</p>
            <p><?= $ideal_min ?> – <?= $ideal_max ?> กก.</p>
          </div>
          <div class="detail-item">
            <p>น้ำหนักปัจจุบัน</p>
            <p><?= $weight ?> กก.</p>
          </div>
        </div>
        <?php if ($bmi >= 18.5 && $bmi <= 22.9): ?>
          <div class="summary-text">✓ น้ำหนักของคุณอยู่ในเกณฑ์เป้าหมายแล้ว!</div>
        <?php elseif ($bmi < 18.5): ?>
          <div class="summary-text">
            ควรเพิ่มน้ำหนักอีกประมาณ <?= round($ideal_min - $weight, 1) ?> กก.
          </div>
        <?php else: ?>
          <div class="summary-text">
            ควรลดน้ำหนักประมาณ <?= round($weight - $ideal_max, 1) ?> กก.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div><!-- /BMI HERO CARD -->

  <!-- ===== ข้อมูลผู้ใช้ ===== -->
  <div class="info-card">
    <h2>
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
      </svg>
      ข้อมูลผู้รับการประเมิน
    </h2>
    <div class="info-grid">
      <div class="info-item">
        <p>ชื่อ-นามสกุล</p>
        <p><?= htmlspecialchars($fullname) ?></p>
      </div>
      <div class="info-item">
        <p>วันเกิด</p>
        <p><?= thaiDate($dob) ?></p>
      </div>
      <div class="info-item">
        <p>อายุ</p>
        <p><?= $age ?> ปี</p>
      </div>
      <div class="info-item">
        <p>ส่วนสูง</p>
        <p><?= $height ?> ซม.</p>
      </div>
    </div>
  </div><!-- /ข้อมูลผู้ใช้ -->

  <!-- ===== คำแนะนำ ===== -->
  <div class="advice-card">
    <h2>
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
      </svg>
      คำแนะนำสำหรับคุณ
    </h2>
    <div class="advice-box">
      <div class="tip">💡 <?= $c['tip'] ?></div>
      <div class="text"><?= htmlspecialchars($c['advice']) ?></div>
    </div>
    <div class="disclaimer">
      * ค่า BMI เป็นเพียงเครื่องมือประเมินเบื้องต้น ควรปรึกษาแพทย์เพื่อการวินิจฉัยที่ถูกต้อง
    </div>
  </div><!-- /คำแนะนำ -->

  <!-- ===== เกณฑ์อ้างอิง BMI ===== -->
  <div class="reference-card">
    <h2>เกณฑ์มาตรฐาน BMI สำหรับคนเอเชีย</h2>
    <ul class="reference-list">
      <?php
      $ranges = [
        ['ผอมเกินไป',                   '< 18.5',      '#3b82f6'],
        ['น้ำหนักปกติ (สุขภาพดี)',      '18.5 – 22.9', '#16a34a'],
        ['น้ำหนักเกินเล็กน้อย (ท้วม)', '23.0 – 24.9', '#ca8a04'],
        ['อ้วน ระดับ 1',                '25.0 – 29.9', '#ea580c'],
        ['อ้วนมาก ระดับ 2',             '≥ 30.0',      '#dc2626'],
      ];
      foreach ($ranges as [$label, $range, $color]):
        $active = false;
        if ($label === 'ผอมเกินไป'                   && $bmi < 18.5)               $active = true;
        if ($label === 'น้ำหนักปกติ (สุขภาพดี)'      && $bmi >= 18.5 && $bmi <= 22.9) $active = true;
        if ($label === 'น้ำหนักเกินเล็กน้อย (ท้วม)'  && $bmi >= 23.0 && $bmi <= 24.9) $active = true;
        if ($label === 'อ้วน ระดับ 1'                && $bmi >= 25.0 && $bmi <= 29.9) $active = true;
        if ($label === 'อ้วนมาก ระดับ 2'             && $bmi >= 30.0)               $active = true;
      ?>
      <li class="<?= $active ? 'active' : '' ?>" style="border-left-color: <?= $color ?>;">
        <span class="label">
          <?php if ($active): ?><span class="highlight">คุณอยู่ที่นี่</span><?php endif; ?>
          <?= $label ?>
        </span>
        <span class="range" style="color: <?= $color ?>"><?= $range ?></span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div><!-- /เกณฑ์อ้างอิง -->

  <!-- ===== ปุ่มกลับหน้าหลัก ===== -->
  <div>
    <a href="index_Fha.php" class="back-button">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
      </svg>
      กลับหน้าหลัก
    </a>
  </div>

</main>

<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer>
  <div>
    <p>© <?= date('Y') ?> ระบบบริหารจัดการสุขภาพดิจิทัล. สงวนลิขสิทธิ์.</p>
    <div class="footer-links">
      <a href="#">นโยบายความเป็นส่วนตัว</a>
      <a href="#">ข้อกำหนดการใช้งาน</a>
    </div>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
