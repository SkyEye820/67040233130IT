<?php
// Include necessary files
require_once 'db_Fha.php';
require_once 'functions_Fha.php';

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>คำนวณ BMI | HealthCheck Thai</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
<style>
  /* ===== ตั้งค่าพื้นฐาน ===== */
  * { box-sizing: border-box; margin: 0; padding: 0; }
  
  @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.8; } }
  
  body {
    font-family: 'Sarabun', sans-serif;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
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
    text-shadow: 0 0 20px rgba(96, 165, 250, 0.5);
  }

  nav .logo svg {
    width: 32px;
    height: 32px;
    stroke: #60a5fa;
    filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.3));
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
    box-shadow: 0 4px 12px rgba(96, 165, 250, 0.3);
  }

  nav .nav-links a.active {
    background: #60a5fa;
    color: white;
    box-shadow: 0 4px 16px rgba(96, 165, 250, 0.4);
  }

  nav .nav-links a i {
    font-size: 18px;
  }

  /* ===== HERO SECTION ===== */
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
    margin-bottom: 20px;
    font-weight: 900;
    letter-spacing: -0.5px;
  }

  header p {
    font-size: 18px;
    color: #64748b;
    margin: 0;
    font-weight: 500;
  }

  /* ===== MAIN LAYOUT ===== */
  main {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
    margin-bottom: 50px;
  }

  /* ===== FORM CARD ===== */
  section {
    background: white;
    border-radius: 20px;
    padding: 45px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    animation: fadeIn 0.6s ease-out;
    border: 1px solid rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
    grid-column: 1;
  }

  section .card-header {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 3px solid transparent;
    border-image: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%) 1;
  }

  section .card-header svg {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    padding: 8px;
    border-radius: 12px;
    color: white;
    stroke-width: 1.5;
    flex-shrink: 0;
  }

  section .card-header h2 {
    font-size: 28px;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
    font-weight: 800;
  }

  /* ===== ERROR BOX ===== */
  .error-box {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border-left: 5px solid #dc2626;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 30px;
    display: flex;
    gap: 14px;
    align-items: flex-start;
    animation: slideDown 0.4s ease-out;
  }

  .error-box svg {
    width: 28px;
    height: 28px;
    color: #dc2626;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .error-box p {
    color: #7f1d1d;
    font-weight: 600;
    margin: 0;
  }

  /* ===== FORM GROUPS ===== */
  form > div {
    margin-bottom: 25px;
  }

  form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  form .form-group {
    display: flex;
    flex-direction: column;
  }

  form label {
    font-weight: 700;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 12px;
    font-size: 16px;
    display: flex;
    gap: 6px;
    align-items: center;
  }

  form label span {
    color: #dc2626;
  }

  form input[type="text"],
  form input[type="date"],
  form input[type="number"],
  form div.age-display {
    padding: 16px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    font-family: 'Sarabun', sans-serif;
    font-size: 16px;
    transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
    background: #f9fbff;
  }

  form input[type="text"]:focus,
  form input[type="date"]:focus,
  form input[type="number"]:focus {
    outline: none;
    border-color: #6366f1;
    background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
    box-shadow: 0 0 16px rgba(99, 102, 241, 0.2);
    transform: translateY(-2px);
  }

  form input::placeholder {
    color: #cbd5e1;
  }

  .input-group {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .input-group input {
    flex: 1;
  }

  .input-group span {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 16px 18px;
    border-radius: 14px;
    border: 2px solid #e2e8f0;
    font-weight: 800;
    color: #475569;
    min-width: 55px;
    text-align: center;
    font-size: 15px;
  }

  .age-display {
    background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
    border: 2px solid #c7d2fe;
    color: #6366f1;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 50px;
    font-size: 16px;
  }

  /* ===== BUTTON ROW ===== */
  .button-group {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 15px;
    margin-top: 40px;
    padding-top: 25px;
    border-top: 2px solid #f1f5f9;
  }

  button {
    padding: 14px 28px;
    border: none;
    border-radius: 12px;
    font-family: 'Sarabun', sans-serif;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }

  button svg {
    width: 22px;
    height: 22px;
  }

  #clearBtn {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #475569;
    border: 2px solid #cbd5e1;
  }

  #clearBtn:hover {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(100, 116, 139, 0.2);
  }

  button[type="submit"] {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
    color: white;
  }

  button[type="submit"]:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 12px 32px rgba(99, 102, 241, 0.4);
  }

  button[type="submit"]:active {
    transform: translateY(-2px) scale(0.98);
  }

  /* ===== SIDEBAR ===== */
  aside {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    grid-column: 1;
  }

  aside > div {
    background: white;
    padding: 32px;
    border-radius: 20px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.5);
    backdropfilter: blur(10px);
    animation: fadeIn 0.6s ease-out 0.1s both;
    position: relative;
    overflow: hidden;
  }

  aside > div::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 0 20px 0 80px;
    opacity: 0.05;
  }

  aside > div:nth-child(1) {
    max-height: fit-content;
  }

  aside h3 {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 800;
    position: relative;
    z-index: 1;
  }

  aside h3 svg {
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    padding: 7px;
    border-radius: 10px;
    color: white;
    flex-shrink: 0;
  }

  aside ul {
    list-style: none;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
  }

  aside li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 15px;
    transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
    border-radius: 8px;
    margin-bottom: 6px;
  }

  aside li:hover {
    background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
    border-bottom-color: #c7d2fe;
    padding-left: 20px;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
  }

  aside li:last-child {
    border-bottom: none;
  }

  aside li span:first-child {
    color: #475569;
    font-weight: 600;
    flex: 1;
  }

  aside li span:last-child {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 800;
    font-size: 14px;
    padding: 4px 12px;
  }

  .formula-box {
    background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
    padding: 24px;
    border-radius: 14px;
    border-left: 5px solid #6366f1;
    margin: 20px 0;
    font-weight: 800;
    color: #1e3a8a;
    font-size: 17px;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
  }

  aside p {
    color: #64748b;
    font-size: 14px;
    margin: 20px 0 0 0;
    line-height: 1.6;
    position: relative;
    z-index: 1;
  }

  /* ===== FOOTER ===== */
  footer {
    background: linear-gradient(90deg, #1e293b 0%, #0f172a 50%, #1e3a8a 100%);
    color: white;
    padding: 50px 30px;
    margin-top: 80px;
    border-top: 2px solid #3b82f6;
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
    font-weight: 500;
  }

  footer .footer-links {
    display: flex;
    gap: 35px;
  }

  footer a {
    color: #93c5fd;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
  }

  footer a:hover {
    color: white;
    transform: translateY(-2px);
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1024px) {
    aside {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 768px) {
    main {
      grid-template-columns: 1fr;
      gap: 20px;
    }

    aside {
      grid-template-columns: 1fr;
      gap: 20px;
    }

    aside > div {
      flex: 1;
    }

    header h1 {
      font-size: 28px;
    }

    form .form-row {
      grid-template-columns: 1fr;
    }

    .button-group {
      grid-template-columns: 1fr;
    }

    nav .nav-links {
      gap: 10px;
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
      <li><a href="index_Fha.php" class="active"><i class="fas fa-home"></i> หัวหน้า</a></li>
      <li><a href="records_Fha.php"><i class="fas fa-list"></i> ข้อมูล</a></li>
    </ul>
  </div>
</nav>

<!-- ============================================================
     HERO — ส่วนหัวหน้า
     ============================================================ -->
<header>
  <div>
    <h1>โปรแกรมคำนวณดัชนีมวลกาย (BMI)</h1>
    <p>กรอกข้อมูลด้านล่างเพื่อคำนวณและบันทึกผล BMI ของคุณ</p>
  </div>
</header>

<!-- ============================================================
     MAIN — เนื้อหาหลัก (2 คอลัมน์: Form | Sidebar)
     ============================================================ -->
<main>

  <!-- ===== FORM CARD ===== -->
  <section>

    <!-- หัว card -->
    <div class="card-header">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
      </svg>
      <h2>กรอกข้อมูลส่วนตัว</h2>
    </div>

    <!-- แสดง error (ถ้ามี) -->
    <?php if ($error): ?>
      <div class="error-box">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
        </svg>
        <p><?= htmlspecialchars($error) ?></p>
      </div>
    <?php endif; ?>

    <!-- ฟอร์ม POST ไปยัง result.php -->
    <form method="POST" action="result_Fha.php" id="bmiForm">

      <!-- ชื่อ-นามสกุล -->
      <div class="form-group">
        <label for="fullName">
          ชื่อ-นามสกุล <span>*</span>
        </label>
        <input
          type="text"
          id="fullName"
          name="fullName"
          required
          placeholder="เช่น นายสมชาย ใจดี"
        />
      </div>

      <!-- วันเกิด + อายุ (2 คอลัมน์) -->
      <div class="form-row">
        <!-- วันเกิด -->
        <div class="form-group">
          <label for="dob">
            วันเกิด <span>*</span>
          </label>
          <input
            type="date"
            id="dob"
            name="dob"
            required
            max="<?= date('Y-m-d') ?>"
          />
        </div>

        <!-- อายุ (แสดงอัตโนมัติผ่าน JS) -->
        <div class="form-group">
          <label>อายุ (คำนวณอัตโนมัติ)</label>
          <div id="agePreview" class="age-display">
            — กรอกวันเกิดเพื่อดูอายุ —
          </div>
        </div>
      </div>

      <!-- น้ำหนัก + ส่วนสูง (2 คอลัมน์) -->
      <div class="form-row">
        <!-- น้ำหนัก -->
        <div class="form-group">
          <label for="weight">
            น้ำหนัก <span>*</span>
          </label>
          <div class="input-group">
            <input
              type="number"
              id="weight"
              name="weight"
              required
              step="0.1"
              min="1"
              max="300"
              placeholder="0.0"
            />
            <span>กก.</span>
          </div>
        </div>

        <!-- ส่วนสูง -->
        <div class="form-group">
          <label for="height">
            ส่วนสูง <span>*</span>
          </label>
          <div class="input-group">
            <input
              type="number"
              id="height"
              name="height"
              required
              min="50"
              max="300"
              placeholder="0"
            />
            <span>ซม.</span>
          </div>
        </div>
      </div>

      <!-- ปุ่ม Clear + Submit (2 คอลัมน์) -->
      <div class="button-group">
        <!-- ปุ่มล้างข้อมูล -->
        <button type="button" id="clearBtn">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
          </svg>
          ล้างข้อมูล
        </button>

        <!-- ปุ่ม Submit -->
        <button type="submit">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
          </svg>
          ยืนยันและคำนวณ
        </button>
      </div>

    </form>

  </section><!-- /FORM CARD -->

  <!-- ===== SIDEBAR ===== -->
  <aside>

    <!-- เกณฑ์ BMI -->
    <div>
      <h3>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
        </svg>
        เกณฑ์ BMI สำหรับคนเอเชีย
      </h3>
      <ul>
        <?php
        $ranges = [
          ['ผอมเกินไป',                   '< 18.5'],
          ['น้ำหนักปกติ (สุขภาพดี)',      '18.5 – 22.9'],
          ['น้ำหนักเกินเล็กน้อย (ท้วม)', '23.0 – 24.9'],
          ['อ้วน ระดับ 1',                '25.0 – 29.9'],
          ['อ้วนมาก ระดับ 2',             '≥ 30.0'],
        ];
        foreach ($ranges as [$label, $range]): ?>
        <li>
          <span><?= $label ?></span>
          <span><?= $range ?></span>
        </li>
        <?php endforeach; ?>
      </ul>
      <p>* ใช้เกณฑ์ WHO สำหรับประชากรเอเชีย</p>
    </div>

    <!-- สูตรคำนวณ -->
    <div>
      <h3><i class="fas fa-calculator"></i> สูตรคำนวณ BMI</h3>
      <div class="formula-box">
        BMI = น้ำหนัก (กก.) ÷ ส่วนสูง² (ม.)
      </div>
      <p>เช่น หนัก 65 กก. สูง 170 ซม. → 65 ÷ 1.70² = <strong>22.5</strong></p>
    </div>

  </aside><!-- /SIDEBAR -->

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

<!-- ============================================================
     JavaScript
     ============================================================ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
  // ===== แสดงอายุ preview เมื่อเลือกวันเกิด =====
  document.getElementById('dob').addEventListener('change', function () {
    const dob   = new Date(this.value);
    const today = new Date();
    const el    = document.getElementById('agePreview');

    if (!this.value || dob > today) {
      el.textContent = '— กรอกวันเกิดเพื่อดูอายุ —';
      return;
    }

    let years  = today.getFullYear() - dob.getFullYear();
    let months = today.getMonth()    - dob.getMonth();
    let days   = today.getDate()     - dob.getDate();
    if (days   < 0) months--;
    if (months < 0) { years--; months += 12; }

    el.textContent = years + ' ปี ' + Math.abs(months) + ' เดือน';
  });

  // ===== ล้างข้อมูลใน Form =====
  document.getElementById('clearBtn').addEventListener('click', function () {
    document.getElementById('bmiForm').reset();
    const el = document.getElementById('agePreview');
    el.textContent = '— กรอกวันเกิดเพื่อดูอายุ —';
  });
</script>

</body>
</html>
