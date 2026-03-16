<?php
require_once 'db_Fha.php';
require_once 'functions_Fha.php';

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'DESC';
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

// ตรวจสอบการค้นหา
$where = '';
$params = [];
if ($search) {
    $where = 'WHERE fullname LIKE :search';
    $params[':search'] = '%' . $search . '%';
}

// ดึงข้อมูล
try {
    $query = "SELECT * FROM bmi_records $where ORDER BY $sort $order";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $records = $stmt->fetchAll();
} catch (PDOException $e) {
    $records = [];
    $error = 'ไม่สามารถดึงข้อมูลได้: ' . $e->getMessage();
}
?>
<html lang="th">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>รายชื่อข้อมูล | HealthCheck Thai</title>
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

  /* NAVBAR */
  nav {
    background: linear-gradient(90deg, #1e293b 0%, #3b82f6 50%, #6366f1 100%);
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
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

  nav .nav-links a:hover,
  nav .nav-links a.active {
    background: #60a5fa;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(96,165,250,0.3);
  }

  /* HEADER */
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
    color: #64748b;
    margin: 0;
    font-weight: 500;
    font-size: 18px;
  }

  /* MAIN */
  main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    margin-bottom: 50px;
  }

  /* SEARCH BAR */
  .search-section {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    margin-bottom: 40px;
    display: flex;
    gap: 20px;
    align-items: flex-end;
    flex-wrap: wrap;
    animation: fadeIn 0.6s ease-out;
  }

  .search-group {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 250px;
  }

  .search-group label {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    margin-bottom: 10px;
    font-size: 15px;
  }

  .search-group input,
  .search-group select {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-family: 'Sarabun', sans-serif;
    font-size: 16px;
    transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
  }

  .search-group input:focus,
  .search-group select:focus {
    outline: none;
    border-color: #6366f1;
    background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
    box-shadow: 0 0 12px rgba(99,102,241,0.25);
  }

  .button-group {
    display: flex;
    gap: 12px;
  }

  button {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-family: 'Sarabun', sans-serif;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
  }

  .btn-search {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
  }

  .btn-search:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 12px 32px rgba(99,102,241,0.4);
  }

  .btn-reset {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #475569;
    border: 2px solid #cbd5e1;
  }

  .btn-reset:hover {
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    transform: translateY(-3px);
  }

  .btn-new {
    background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
    color: white;
    margin-left: auto;
  }

  .btn-new:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 12px 32px rgba(22,163,74,0.4);
  }

  /* TABLE */
  .table-wrapper {
    background: white;
    border-radius: 20px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    overflow: hidden;
    animation: fadeIn 0.6s ease-out;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 3px solid #e2e8f0;
  }

  th {
    padding: 18px;
    text-align: left;
    font-weight: 800;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 15px;
    white-space: nowrap;
  }

  th a {
    color: inherit;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    font-weight: 800;
  }

  th a:hover {
    opacity: 0.7;
  }

  td {
    padding: 16px 18px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 15px;
  }

  tbody tr {
    transition: all 0.3s ease;
  }

  tbody tr:hover {
    background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
  }

  .bmi-badge {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 20px;
    font-weight: 800;
    font-size: 13px;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  /* EMPTY STATE */
  .empty-state {
    text-align: center;
    padding: 60px 20px;
  }

  .empty-state i {
    font-size: 48px;
    color: #cbd5e1;
    margin-bottom: 15px;
  }

  .empty-state p {
    color: #64748b;
    margin-bottom: 20px;
  }

  .empty-state a {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
  }

  .empty-state a:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102,126,234,0.3);
  }

  /* SUCCESS BOX */
  .success-box {
    background: #dcfce7;
    border-left: 5px solid #16a34a;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
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

  /* ERROR BOX */
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

  /* ACTION BUTTONS */
  .action-buttons {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .btn-action {
    padding: 8px 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
    text-decoration: none;
    color: white;
  }

  .btn-edit {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  }

  .btn-edit:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 16px rgba(59,130,246,0.3);
  }

  .btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
  }

  .btn-delete:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 16px rgba(239,68,68,0.3);
  }

  /* FOOTER */
  footer {
    background: rgba(30,58,138,0.95);
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

  @media (max-width: 768px) {
    .search-section {
      flex-direction: column;
    }

    .button-group {
      width: 100%;
    }

    .btn-new {
      margin-left: 0;
      width: 100%;
      justify-content: center;
    }

    table {
      font-size: 12px;
    }

    th, td {
      padding: 10px 8px;
    }

    footer > div {
      flex-direction: column;
      gap: 20px;
      text-align: center;
    }
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
  <h1>รายชื่อข้อมูล BMI</h1>
  <p>ค้นหาและดูข้อมูลการประเมิน BMI ทั้งหมด</p>
</header>

<!-- MAIN -->
<main>

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

  <!-- SEARCH SECTION -->
  <div class="search-section">
    <form method="GET" style="display: flex; gap: 15px; align-items: flex-end; width: 100%; flex-wrap: wrap;">
      <div class="search-group" style="flex: 1; min-width: 250px;">
        <label for="search">ค้นหาตามชื่อ</label>
        <input 
          type="text" 
          id="search" 
          name="search" 
          placeholder="เช่น นายสมชาย ใจดี"
          value="<?= htmlspecialchars($search) ?>"
        />
      </div>

      <div class="search-group" style="min-width: 200px;">
        <label for="sort">เรียงลำดับ</label>
        <select id="sort" name="sort">
          <option value="created_at" <?= $sort === 'created_at' ? 'selected' : '' ?>>วันที่บันทึก</option>
          <option value="fullname" <?= $sort === 'fullname' ? 'selected' : '' ?>>ชื่อ</option>
          <option value="bmi" <?= $sort === 'bmi' ? 'selected' : '' ?>>ค่า BMI</option>
          <option value="age" <?= $sort === 'age' ? 'selected' : '' ?>>อายุ</option>
        </select>
      </div>

      <div class="button-group">
        <button type="submit" class="btn-search">
          <i class="fas fa-search"></i> ค้นหา
        </button>
        <a href="records_Fha.php" class="btn-reset" style="text-decoration: none; display: flex; align-items: center;">
          <i class="fas fa-redo"></i> ล้าง
        </a>
        <a href="index_Fha.php" class="btn-new" style="text-decoration: none; margin-left: auto;">
          <i class="fas fa-plus"></i> เพิ่มใหม่
        </a>
      </div>
    </form>
  </div>

  <!-- TABLE SECTION -->
  <div class="table-wrapper">
    <?php if (count($records) > 0): ?>
      <table>
        <thead>
          <tr>
            <th style="width: 60px;">รหัส</th>
            <th>ชื่อ-นามสกุล</th>
            <th>อายุ</th>
            <th style="text-align: center;">ส่วนสูง</th>
            <th style="text-align: center;">น้ำหนัก</th>
            <th style="text-align: center;">BMI</th>
            <th>สถานะ</th>
            <th>วันที่บันทึก</th>
            <th style="text-align: center; width: 150px;">การดำเนินการ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $record): ?>
          <tr>
            <td><strong>#<?= $record['id'] ?></strong></td>
            <td><?= htmlspecialchars($record['fullname']) ?></td>
            <td style="text-align: center;"><?= $record['age'] ?> ปี</td>
            <td style="text-align: center;"><?= $record['height'] ?> cm</td>
            <td style="text-align: center;"><?= $record['weight'] ?> kg</td>
            <td style="text-align: center;">
              <span class="bmi-badge" style="background-color: <?= getBMIColor($record['bmi']) ?>;">
                <?= number_format($record['bmi'], 1) ?>
              </span>
            </td>
            <td><?= getBMIStatus($record['bmi']) ?></td>
            <td><?= thaiDate(substr($record['created_at'], 0, 10)) ?></td>
            <td style="text-align: center;">
              <div class="action-buttons">
                <a href="edit_Fha.php?id=<?= $record['id'] ?>" class="btn-action btn-edit">
                  <i class="fas fa-edit"></i> แก้ไข
                </a>
                <button type="button" class="btn-action btn-delete" onclick="confirmDelete(<?= $record['id'] ?>)">
                  <i class="fas fa-trash"></i> ลบ
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>ไม่พบข้อมูล</p>
        <a href="index_Fha.php"><i class="fas fa-plus"></i> เพิ่มข้อมูลใหม่</a>
      </div>
    <?php endif; ?>
  </div>

</main>

<!-- FOOTER -->
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

<script>
function confirmDelete(id) {
    if (confirm('คุณแน่ใจว่าต้องการลบข้อมูลนี้หรือไม่?')) {
        window.location.href = 'delete_Fha.php?id=' + id;
    }
}
</script>
</body>
</html>
