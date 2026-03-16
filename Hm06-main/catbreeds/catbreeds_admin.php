<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CatBreeds Admin</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    * { box-sizing: border-box; }

    :root{
      --bg-dark: #0a0314;
      --bg-darker: #1a0a2e;
      --purple-main: #a78bfa;
      --purple-bright: #d8b4fe;
      --purple-deep: #6d28d9;
      --accent-pink: #e91e63;
      --accent-violet: #9c27b0;
      --text-light: #f3f4f6;
      --text-muted: #9ca3af;
    }

    body{
      background: linear-gradient(180deg, #0a0314 0%, #1a0a2e 50%, #2d1440 100%);
      color: var(--text-light);
      font-family: "Poppins", "Kanit", "Prompt", sans-serif;
      overflow-x: hidden;
      min-height: 100vh;
    }

    /* Minimalist Header */
    .topbar {
      padding: 18px 0;
      border-bottom: 2px solid rgba(167, 139, 250, 0.15);
      background: rgba(10, 3, 20, 0.6);
      backdrop-filter: blur(10px);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .topbar .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .brand .logo {
      width: 45px;
      height: 45px;
      border-radius: 8px;
      background: linear-gradient(135deg, #a78bfa 0%, #e91e63 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      box-shadow: 0 8px 20px rgba(169, 139, 250, 0.3);
    }

    .brand h1 {
      font-size: 1.3rem;
      font-weight: 700;
      margin: 0;
      color: var(--purple-bright);
      letter-spacing: -0.3px;
    }

    .brand .sub {
      font-size: 0.75rem;
      color: var(--text-muted);
      font-weight: 500;
    }

    .chip {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 14px;
      border-radius: 20px;
      font-size: 0.85rem;
      background: rgba(169, 139, 250, 0.1);
      border: 1px solid rgba(169, 139, 250, 0.2);
      color: var(--purple-main);
      transition: all .25s ease;
    }

    .chip:hover {
      background: rgba(169, 139, 250, 0.2);
      border-color: var(--purple-main);
    }

    .btn {
      font-weight: 600;
      border-radius: 10px;
      padding: 10px 18px;
      transition: all .25s ease;
    }

    .btn-soft {
      border: 1.5px solid rgba(169, 139, 250, 0.3);
      background: rgba(169, 139, 250, 0.1);
      color: var(--text-light);
    }

    .btn-soft:hover {
      background: rgba(169, 139, 250, 0.2);
      border-color: var(--purple-main);
      color: var(--purple-bright);
      box-shadow: 0 8px 24px rgba(169, 139, 250, 0.2);
    }

    .btn-grad {
      border: none;
      background: linear-gradient(135deg, var(--purple-main) 0%, var(--accent-pink) 100%);
      color: white;
      box-shadow: 0 12px 30px rgba(169, 139, 250, 0.35);
    }

    .btn-grad:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 40px rgba(169, 139, 250, 0.4);
      color: white;
    }

    .glass {
      background: rgba(26, 10, 46, 0.7);
      border: 1px solid rgba(167, 139, 250, 0.15);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      padding: 0;
      overflow: hidden;
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
      transition: all .3s ease;
    }

    .glass:hover {
      border-color: var(--purple-main);
      box-shadow: 0 20px 50px rgba(169, 139, 250, 0.2), 0 0 30px rgba(233, 30, 99, 0.15);
    }

    .glass-header {
      padding: 20px 24px;
      border-bottom: 1px solid rgba(167, 139, 250, 0.15);
      background: linear-gradient(90deg, rgba(169, 139, 250, 0.08) 0%, transparent 100%);
    }

    .glass-header i {
      color: var(--purple-main);
      margin-right: 8px;
    }

    .glass-body {
      padding: 24px;
    }

    label.form-label {
      color: var(--text-light);
      font-weight: 600;
      margin-bottom: 8px;
    }

    .help {
      color: var(--text-muted);
      font-size: 0.85rem;
    }

    .form-control,
    .form-check-input {
      background: rgba(45, 20, 64, 0.7);
      border: 1px solid rgba(167, 139, 250, 0.2);
      color: var(--text-light);
      border-radius: 8px;
      transition: all .25s ease;
    }

    .form-control::placeholder {
      color: var(--text-muted);
    }

    .form-control:focus {
      background: rgba(45, 20, 64, 1);
      border-color: var(--purple-main);
      box-shadow: 0 0 20px rgba(167, 139, 250, 0.2);
      color: var(--text-light);
    }

    .table {
      --bs-table-bg: transparent !important;
      --bs-table-color: var(--text-light) !important;
      --bs-table-border-color: rgba(167, 139, 250, 0.15) !important;
      --bs-table-striped-bg: rgba(169, 139, 250, 0.05) !important;
      --bs-table-hover-bg: rgba(169, 139, 250, 0.1) !important;
    }

    .table thead th {
      background: rgba(169, 139, 250, 0.1) !important;
      color: var(--purple-bright) !important;
      border-bottom: 2px solid var(--purple-main) !important;
      font-weight: 700;
    }

    .table tbody td {
      border-color: rgba(167, 139, 250, 0.1) !important;
      padding: 16px;
    }

    .table tbody tr:hover td {
      background: rgba(169, 139, 250, 0.08) !important;
    }

    .thumb {
      width: 56px;
      height: 56px;
      border-radius: 10px;
      object-fit: cover;
      border: 1px solid rgba(167, 139, 250, 0.2);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      transition: all .25s ease;
    }

    .thumb:hover {
      border-color: var(--purple-main);
      box-shadow: 0 12px 30px rgba(169, 139, 250, 0.2);
    }

    .thumb-ph {
      width: 56px;
      height: 56px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(169, 139, 250, 0.1);
      border: 1px solid rgba(167, 139, 250, 0.2);
      color: var(--purple-main);
    }

    .badge-pub {
      background: rgba(169, 139, 250, 0.2);
      border: 1px solid var(--purple-main);
      color: var(--purple-bright);
    }

    .badge-hide {
      background: rgba(156, 163, 175, 0.1);
      border: 1px solid rgba(156, 163, 175, 0.3);
      color: var(--text-muted);
    }

    .text-white-50 {
      color: var(--text-muted) !important;
    }

    .modal-content {
      background: linear-gradient(135deg, rgba(26, 10, 46, 0.95) 0%, rgba(45, 20, 64, 0.95) 100%);
      border: 2px solid rgba(169, 139, 250, 0.25);
      backdrop-filter: blur(30px);
      color: var(--text-light);
      border-radius: 16px;
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8), 0 0 60px rgba(169, 139, 250, 0.3);
    }

    .modal-header {
      border-bottom: 1px solid rgba(169, 139, 250, 0.15);
      padding: 24px 28px;
      background: linear-gradient(90deg, rgba(169, 139, 250, 0.1) 0%, transparent 100%);
    }

    .modal-footer {
      border-top: 1px solid rgba(169, 139, 250, 0.15);
      padding: 18px 28px;
      background: rgba(45, 20, 64, 0.4);
    }

    .modal-header .fw-bold {
      color: var(--purple-bright);
      font-size: 1.25rem;
    }

    .btn-close-white {
      opacity: 0.7;
      transition: all 0.25s ease;
    }

    .btn-close-white:hover {
      opacity: 1;
    }

    @media (max-width: 768px) {
      .modal-dialog {
        margin: 10px;
      }
    }
  </style>
</head>

<body>
  <div class="topbar">
    <div class="container">
      <div class="brand">
        <div class="logo"><i class="bi bi-gear-fill"></i></div>
        <div>
          <h1>CatBreeds Admin</h1>
          <div class="sub">Manage • Edit • Publish • Delete (via API)</div>
        </div>
      </div>

      <div style="display:flex; gap:12px; align-items:center;">
        <span class="chip"><i class="bi bi-gem"></i> Purple Magic Admin</span>
        <a class="btn btn-soft" href="catbreeds.php"><i class="bi bi-box-arrow-up-right"></i> View Site</a>
      </div>
    </div>
  </div>

  <div class="container pb-5">
    <div class="row g-4">
      <!-- Form Create -->
      <div class="col-lg-5">
        <div class="glass">
          <div class="glass-header">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-plus-circle text-white"></i>
              <div class="text-white fw-bold">เพิ่มสายพันธุ์ใหม่</div>
            </div>
            <div class="help mt-1">ติ๊ก “แสดงบนหน้าบ้าน” เพื่อให้ขึ้นหน้าหลัก</div>
          </div>

          <div class="glass-body">
            <form id="addForm" enctype="multipart/form-data">
              <div class="mb-2">
                <label class="form-label">ชื่อสายพันธุ์ (ไทย) *</label>
                <input class="form-control" name="name_th" placeholder="เช่น เปอร์เซีย" required>
              </div>
              <div class="mb-2">
                <label class="form-label">ชื่อสายพันธุ์ (อังกฤษ) *</label>
                <input class="form-control" name="name_en" placeholder="เช่น Persian" required>
              </div>
              <div class="mb-2">
                <label class="form-label">คำอธิบาย *</label>
                <textarea class="form-control" name="description" rows="3" placeholder="คำอธิบายสั้น ๆ" required></textarea>
              </div>
              <div class="mb-2">
                <label class="form-label">ลักษณะเด่น</label>
                <textarea class="form-control" name="characteristics" rows="2" placeholder="เช่น ขนยาว หน้าสั้น"></textarea>
              </div>
              <div class="mb-2">
                <label class="form-label">การดูแล</label>
                <textarea class="form-control" name="care_instructions" rows="2" placeholder="เช่น แปรงขนทุกวัน"></textarea>
              </div>
              <div class="mb-2">
                <label class="form-label">รูปภาพ</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <div class="help mt-1">รองรับ JPG, PNG, GIF, WEBP</div>
              </div>

              <div class="form-check my-3">
                <input class="form-check-input" type="checkbox" id="add_visible" name="is_visible" checked>
                <label class="form-check-label text-white" for="add_visible">แสดงบนหน้าบ้าน</label>
              </div>

              <button class="btn btn-grad w-100 py-2" type="submit">
                <i class="bi bi-save2"></i> บันทึกข้อมูล
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="col-lg-7">
        <div class="glass">
          <div class="glass-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-list-ul text-white"></i>
              <div class="text-white fw-bold">รายการสายพันธุ์</div>
            </div>
            <div class="chip" id="countText"><i class="bi bi-collection"></i> ทั้งหมด 0 รายการ</div>
          </div>

          <div class="glass-body">
            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead>
                  <tr>
                    <th style="width:80px;">รูป</th>
                    <th>ชื่อ</th>
                    <th style="width:110px;">สถานะ</th>
                    <th class="text-end" style="width:280px;">จัดการ</th>
                  </tr>
                </thead>
                <tbody id="rows"></tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div class="fw-bold"><i class="bi bi-pencil-square"></i> แก้ไขข้อมูลสายพันธุ์</div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <form id="editForm" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">

            <div class="row g-3">
              <div class="col-md-8">
                <div class="mb-2">
                  <label class="form-label">ชื่อสายพันธุ์ (ไทย) *</label>
                  <input class="form-control" name="name_th" id="edit_name_th" required>
                </div>
                <div class="mb-2">
                  <label class="form-label">ชื่อสายพันธุ์ (อังกฤษ) *</label>
                  <input class="form-control" name="name_en" id="edit_name_en" required>
                </div>
              </div>

              <div class="col-md-4">
                <label class="form-label">รูปปัจจุบัน</label>
                <div class="d-flex align-items-center gap-3">
                  <img id="edit_preview" class="thumb" src="" alt="" style="display:none;">
                  <div id="edit_preview_ph" class="thumb-ph"><i class="bi bi-image"></i></div>
                </div>
                <div class="help mt-2">ถ้าอัปโหลดรูปใหม่ จะแทนรูปเดิม</div>
              </div>

              <div class="col-12">
                <div class="mb-2">
                  <label class="form-label">คำอธิบาย *</label>
                  <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">ลักษณะเด่น</label>
                <textarea class="form-control" name="characteristics" id="edit_characteristics" rows="2"></textarea>
              </div>

              <div class="col-md-6">
                <label class="form-label">การดูแล</label>
                <textarea class="form-control" name="care_instructions" id="edit_care" rows="2"></textarea>
              </div>

              <div class="col-md-8">
                <label class="form-label">อัปโหลดรูปใหม่</label>
                <input type="file" class="form-control" name="image" accept="image/*" id="edit_image">
              </div>

              <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="edit_visible" name="is_visible">
                  <label class="form-check-label" for="edit_visible">แสดงบนหน้าบ้าน</label>
                </div>
              </div>

            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-soft" type="button" data-bs-dismiss="modal">ยกเลิก</button>
            <button class="btn btn-grad" type="submit"><i class="bi bi-save2"></i> บันทึกการแก้ไข</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function escapeHtml(s){
      return (s ?? "").toString().replace(/[&<>"']/g, m => ({
        "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
      }[m]));
    }

    async function fetchAll(){
      const res = await fetch("api/breeds_list.php?all=1", { cache: "no-store" });
      return await res.json();
    }

    async function render(){
      const json = await fetchAll();
      const rows = document.getElementById("rows");
      const countText = document.getElementById("countText");

      if(json.error){
        rows.innerHTML = `<tr><td colspan="4" class="text-danger">${escapeHtml(json.error)}</td></tr>`;
        return;
      }

      const list = json.data || [];
      countText.innerHTML = `<i class="bi bi-collection"></i> ทั้งหมด ${list.length} รายการ`;

      if(list.length === 0){
        rows.innerHTML = `<tr><td colspan="4" class="text-white-50">ยังไม่มีข้อมูล</td></tr>`;
        return;
      }

      rows.innerHTML = list.map(b => {
        const visible = parseInt(b.is_visible) === 1;
        const badge = visible
          ? `<span class="badge badge-pub">เผยแพร่</span>`
          : `<span class="badge badge-hide">ซ่อน</span>`;

        const toggleTo = visible ? 0 : 1;
        const toggleText = visible ? "ซ่อน" : "เผยแพร่";

        const imgCell = b.image_url
          ? `<img class="thumb" src="${escapeHtml(b.image_url)}" alt="">`
          : `<div class="thumb-ph"><i class="bi bi-image"></i></div>`;

        // ส่ง object เข้า openEdit แบบปลอดภัย
        const payload = encodeURIComponent(JSON.stringify(b));

        return `
          <tr>
            <td>${imgCell}</td>
            <td>
              <div class="fw-semibold">${escapeHtml(b.name_th)}</div>
              <div class="text-white-50 small">${escapeHtml(b.name_en)}</div>
            </td>
            <td>${badge}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-soft me-1" onclick="openEdit('${payload}')">
                <i class="bi bi-pencil"></i> แก้ไข
              </button>
              <button class="btn btn-sm btn-soft me-1" onclick="toggleVisibility(${b.id}, ${toggleTo})">
                <i class="bi bi-eye${toggleTo===1? '':'-slash'}"></i> ${toggleText}
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="deleteBreed(${b.id})">
                <i class="bi bi-trash"></i> ลบ
              </button>
            </td>
          </tr>
        `;
      }).join("");
    }

    // CREATE
    document.getElementById("addForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const fd = new FormData(e.target);

      const res = await fetch("api/breed_create.php", { method:"POST", body: fd });
      const json = await res.json();

      if(json.error){ alert(json.error); return; }

      e.target.reset();
      document.getElementById("add_visible").checked = true;
      await render();
    });

    // TOGGLE
    async function toggleVisibility(id, is_visible){
      const fd = new FormData();
      fd.append("id", id);
      fd.append("is_visible", is_visible);

      const res = await fetch("api/breed_toggle.php", { method:"POST", body: fd });
      const json = await res.json();

      if(json.error){ alert(json.error); return; }
      await render();
    }

    // DELETE
    async function deleteBreed(id){
      if(!confirm("ต้องการลบรายการนี้หรือไม่?")) return;

      const fd = new FormData();
      fd.append("id", id);

      const res = await fetch("api/breed_delete.php", { method:"POST", body: fd });
      const json = await res.json();

      if(json.error){ alert(json.error); return; }
      await render();
    }

    // EDIT
    const editModal = new bootstrap.Modal(document.getElementById("editModal"));

    function setPreview(url){
      const img = document.getElementById("edit_preview");
      const ph = document.getElementById("edit_preview_ph");

      if(url){
        img.src = url;
        img.style.display = "block";
        ph.style.display = "none";
      }else{
        img.src = "";
        img.style.display = "none";
        ph.style.display = "flex";
      }
    }

    function openEdit(encodedJson){
      const b = JSON.parse(decodeURIComponent(encodedJson));

      document.getElementById("edit_id").value = b.id;
      document.getElementById("edit_name_th").value = b.name_th ?? "";
      document.getElementById("edit_name_en").value = b.name_en ?? "";
      document.getElementById("edit_description").value = b.description ?? "";
      document.getElementById("edit_characteristics").value = b.characteristics ?? "";
      document.getElementById("edit_care").value = b.care_instructions ?? "";
      document.getElementById("edit_visible").checked = (parseInt(b.is_visible) === 1);

      document.getElementById("edit_image").value = "";
      setPreview(b.image_url || "");

      editModal.show();
    }

    document.getElementById("edit_image").addEventListener("change", (e) => {
      const file = e.target.files && e.target.files[0];
      if(!file) return;
      setPreview(URL.createObjectURL(file));
    });

    document.getElementById("editForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const fd = new FormData(e.target);

      const res = await fetch("api/breed_update.php", { method:"POST", body: fd });
      const json = await res.json();

      if(json.error){ alert(json.error); return; }

      editModal.hide();
      await render();
    });

    render();
  </script>
</body>
</html>
