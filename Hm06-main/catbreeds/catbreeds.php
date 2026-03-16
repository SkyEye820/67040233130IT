<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CatBreeds Showcase</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    * { 
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

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

    .topbar-right {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      background: rgba(169, 139, 250, 0.1);
      border-radius: 20px;
      font-size: 0.85rem;
      color: var(--purple-main);
      border: 1px solid rgba(169, 139, 250, 0.2);
    }

    .btn-admin {
      padding: 8px 14px;
      background: rgba(169, 139, 250, 0.15);
      border: 1px solid rgba(169, 139, 250, 0.3);
      color: var(--purple-bright);
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-admin:hover {
      background: rgba(169, 139, 250, 0.25);
      border-color: var(--purple-main);
      box-shadow: 0 6px 18px rgba(169, 139, 250, 0.2);
      color: var(--purple-bright);
    }

    /* Centered Hero */
    .hero-section {
      text-align: center;
      padding: 60px 20px;
      margin-bottom: 50px;
    }

    .hero-tag {
      display: inline-block;
      padding: 8px 16px;
      background: rgba(217, 70, 239, 0.1);
      border: 1px solid rgba(217, 70, 239, 0.3);
      border-radius: 20px;
      color: #d8b4fe;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .hero-title {
      font-size: clamp(2rem, 5vw, 3.2rem);
      font-weight: 800;
      background: linear-gradient(135deg, #d8b4fe 0%, #e91e63 50%, #a78bfa 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 16px;
      letter-spacing: -1px;
      line-height: 1.2;
    }

    .hero-subtitle {
      font-size: 1.1rem;
      color: var(--text-muted);
      max-width: 600px;
      margin: 0 auto 40px;
      line-height: 1.6;
    }

    .search-container {
      max-width: 500px;
      margin: 0 auto;
      display: flex;
      gap: 10px;
    }

    .search-box {
      flex: 1;
      padding: 14px 18px;
      background: rgba(45, 20, 64, 0.7);
      border: 2px solid rgba(167, 139, 250, 0.2);
      border-radius: 8px;
      color: var(--text-light);
      font-size: 0.95rem;
      outline: none;
      transition: all 0.3s ease;
      font-family: inherit;
    }

    .search-box::placeholder {
      color: var(--text-muted);
    }

    .search-box:focus {
      border-color: var(--purple-main);
      background: rgba(45, 20, 64, 1);
      box-shadow: 0 0 20px rgba(167, 139, 250, 0.2);
    }

    .search-info {
      text-align: center;
      margin-top: 16px;
      font-size: 0.85rem;
      color: var(--text-muted);
    }

    /* New Card Design - Horizontal Layout */
    .breeds-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 60px;
    }

    .breed-item {
      background: rgba(26, 10, 46, 0.7);
      border: 1px solid rgba(167, 139, 250, 0.15);
      border-radius: 12px;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      cursor: pointer;
      position: relative;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .breed-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(169, 139, 250, 0.2), transparent);
      transition: left 0.6s ease;
      z-index: 1;
    }

    .breed-item:hover {
      border-color: var(--purple-main);
      transform: translateY(-12px);
      box-shadow: 0 20px 50px rgba(169, 139, 250, 0.2), 0 0 30px rgba(233, 30, 99, 0.15);
    }

    .breed-item:hover::before {
      left: 100%;
    }

    .breed-thumb {
      width: 100%;
      height: 160px;
      background: linear-gradient(135deg, rgba(169, 139, 250, 0.1), rgba(233, 30, 99, 0.1));
      overflow: hidden;
      position: relative;
    }

    .breed-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .breed-item:hover .breed-thumb img {
      transform: scale(1.08);
    }

    .breed-thumb-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      background: rgba(45, 20, 64, 0.5);
    }

    .breed-info {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      position: relative;
      z-index: 2;
    }

    .breed-lang-label {
      font-size: 0.8rem;
      color: var(--purple-main);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }

    .breed-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--purple-bright);
      margin-bottom: 4px;
    }

    .breed-english {
      font-size: 0.9rem;
      color: var(--accent-pink);
      margin-bottom: 12px;
      font-weight: 500;
    }

    .breed-desc {
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.5;
      margin-bottom: 16px;
      flex-grow: 1;
    }

    .breed-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 12px;
      border-top: 1px solid rgba(167, 139, 250, 0.1);
    }

    .breed-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 8px;
      background: rgba(169, 139, 250, 0.1);
      border-radius: 12px;
      font-size: 0.75rem;
      color: var(--purple-main);
    }

    .btn-view {
      padding: 8px 16px;
      background: linear-gradient(135deg, var(--purple-main) 0%, var(--accent-pink) 100%);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-view:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(169, 139, 250, 0.3);
    }

    .btn-view:active {
      transform: translateY(0);
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 80px 20px;
      color: var(--text-muted);
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 16px;
      opacity: 0.5;
    }

    .empty-state p {
      font-size: 1.1rem;
    }

    /* Modern Modal Design */
    .modal-backdrop {
      background: rgba(10, 3, 20, 0.85);
      backdrop-filter: blur(8px);
    }

    .modal.fade .modal-dialog {
      transform: translateY(30px);
      opacity: 0;
    }

    .modal.show .modal-dialog {
      transform: translateY(0);
      opacity: 1;
    }

    .modal-dialog {
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-content {
      background: linear-gradient(135deg, rgba(26, 10, 46, 0.95) 0%, rgba(45, 20, 64, 0.95) 100%);
      border: 2px solid rgba(169, 139, 250, 0.25);
      border-radius: 16px;
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8), 0 0 60px rgba(169, 139, 250, 0.3);
      color: var(--text-light);
    }

    .modal-header {
      border-bottom: 1px solid rgba(169, 139, 250, 0.15);
      padding: 28px 28px 20px;
      background: linear-gradient(90deg, rgba(169, 139, 250, 0.08) 0%, transparent 100%);
    }

    .modal-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--text-light);
    }

    .modal-body {
      padding: 28px;
    }

    .modal-body > img {
      border-radius: 12px;
      border: 2px solid rgba(169, 139, 250, 0.2);
      margin-bottom: 28px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
      transition: all 0.3s ease;
      max-height: 350px;
    }

    .modal-body > img:hover {
      border-color: var(--purple-main);
      box-shadow: 0 20px 50px rgba(169, 139, 250, 0.3);
    }

    .detail-section {
      margin-bottom: 24px;
    }

    .detail-label {
      font-size: 1rem;
      font-weight: 700;
      color: var(--purple-bright);
      margin-bottom: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .detail-content {
      padding: 14px 16px;
      background: rgba(45, 20, 64, 0.5);
      border-left: 3px solid var(--purple-main);
      border-radius: 6px;
      color: var(--text-muted);
      line-height: 1.7;
      font-size: 0.95rem;
    }

    .modal-footer {
      border-top: 1px solid rgba(169, 139, 250, 0.15);
      padding: 16px 28px;
      background: rgba(45, 20, 64, 0.4);
    }

    .btn-close-modal {
      padding: 10px 20px;
      background: rgba(169, 139, 250, 0.15);
      border: 1px solid rgba(169, 139, 250, 0.3);
      color: var(--purple-bright);
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }

    .btn-close-modal:hover {
      background: rgba(169, 139, 250, 0.25);
      border-color: var(--purple-main);
      box-shadow: 0 6px 18px rgba(169, 139, 250, 0.2);
    }

    .img-placeholder {
      width: 100%;
      height: 280px;
      background: rgba(45, 20, 64, 0.6);
      border: 2px dashed rgba(169, 139, 250, 0.2);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      margin-bottom: 28px;
      flex-direction: column;
    }

    .img-placeholder i {
      font-size: 2.5rem;
      margin-bottom: 8px;
      opacity: 0.6;
    }

    .detail-section {
      margin-bottom: 24px;
    }

    .detail-label {
      font-size: 1rem;
      font-weight: 700;
      color: var(--purple-bright);
      margin-bottom: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .detail-content {
      padding: 14px 16px;
      background: rgba(45, 20, 64, 0.5);
      border-left: 4px solid var(--purple-main);
      border-radius: 6px;
      color: var(--text-muted);
      line-height: 1.7;
      font-size: 0.95rem;
    }

    .detail-content:nth-child(1) {
      border-left-color: var(--purple-main);
    }

    .detail-content:nth-child(2) {
      border-left-color: var(--accent-pink);
    }

    .detail-content:nth-child(3) {
      border-left-color: var(--accent-violet);
    }

    @media (max-width: 768px) {
      .breeds-grid {
        grid-template-columns: 1fr;
      }

      .hero-title {
        font-size: 2rem;
      }

      .modal-dialog {
        margin: 10px;
      }
    }
  </style>
</head>

<body>
<body>
  <!-- Minimalist Header -->
  <div class="topbar">
    <div class="container">
      <div class="brand">
        <div class="logo"><i class="bi bi-cat"></i></div>
        <div>
          <h1>CatVerse</h1>
          <div class="sub">Discover & Explore Cat Breeds</div>
        </div>
      </div>
      <div class="topbar-right">
        <span class="status-badge"><i class="bi bi-dot"></i> Live Data</span>
        <a href="catbreeds_admin.php" class="btn-admin"><i class="bi bi-gear"></i> Admin</a>
      </div>
    </div>
  </div>

  <!-- Hero Section -->
  <div class="hero-section">
    <span class="hero-tag"><i class="bi bi-sparkles"></i> Comprehensive Cat Breeder Database</span>
    <h1 class="hero-title">Find Your Purrfect Companion</h1>
    <p class="hero-subtitle">Explore detailed information about cat breeds, characteristics, and care instructions in Thai and English.</p>
    
    <div class="search-container">
      <input type="text" id="q" class="search-box" placeholder="Search by breed name... วิเชียรมาศ, Persian">
      <button onclick="document.getElementById('q').focus()" class="btn-view" style="width: auto;"><i class="bi bi-search"></i></button>
    </div>
    <div class="search-info" id="countText">Loading breed data...</div>
  </div>

  <!-- Breeds Grid -->
  <div class="container">
    <div class="breeds-grid" id="breedList"></div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="m_title"><i class="bi bi-sparkles"></i> Breed Details</h2>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
          <img id="m_img" src="" alt="" style="width:100%;height:auto;max-height:320px;object-fit:cover;display:none;border-radius:14px;border:2px solid rgba(169,139,250,.2);margin-bottom:28px;box-shadow:0 12px 40px rgba(0,0,0,0.4);">
          <div id="m_img_ph" class="img-placeholder" style="display:none;margin-bottom:28px;">
            <i class="bi bi-image"></i>
            <span>No Image Available</span>
          </div>

          <div class="detail-section">
            <div class="detail-label"><i class="bi bi-book"></i> Description</div>
            <div class="detail-content" id="m_desc">-</div>
          </div>

          <div class="detail-section">
            <div class="detail-label"><i class="bi bi-star-fill"></i> Characteristics</div>
            <div class="detail-content" id="m_char">-</div>
          </div>

          <div class="detail-section">
            <div class="detail-label"><i class="bi bi-heart-fill"></i> Care Instructions</div>
            <div class="detail-content" id="m_care">-</div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn-close-modal" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const elList = document.getElementById("breedList");
    const elCount = document.getElementById("countText");
    const elQ = document.getElementById("q");

    let allData = [];

    function escapeHtml(s){
      return (s ?? "").toString().replace(/[&<>"']/g, m => ({
        "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
      }[m]));
    }

    function render(list){
      if(!list || list.length === 0){
        elList.innerHTML = `
          <div style="grid-column: 1/-1;">
            <div class="empty-state">
              <i class="bi bi-inbox"></i>
              <p>No cat breeds found</p>
            </div>
          </div>
        `;
        elCount.textContent = "0 breeds available";
        return;
      }

      elCount.textContent = `${list.length} breed${list.length !== 1 ? 's' : ''} available`;

      elList.innerHTML = list.map(b => {
        const title = `${b.name_th} (${b.name_en})`;
        const desc = (b.description || "");
        const short = desc.length > 85 ? desc.slice(0, 85) + "..." : desc;

        const img = b.image_url ? `
          <img src="${escapeHtml(b.image_url)}" alt="" />
        ` : `
          <div class="breed-thumb-placeholder">
            <i class="bi bi-image"></i>
          </div>
        `;

        const payload = encodeURIComponent(JSON.stringify(b));

        return `
          <div class="breed-item">
            <div class="breed-thumb">
              ${img}
            </div>
            <div class="breed-info">
              <div class="breed-lang-label">Thailand</div>
              <div class="breed-title">${escapeHtml(b.name_th)}</div>
              <div class="breed-english">${escapeHtml(b.name_en)}</div>
              <div class="breed-desc">${escapeHtml(short)}</div>
              
              <div class="breed-footer">
                <span class="breed-badge"><i class="bi bi-heart-fill"></i> Featured</span>
                <button class="btn-view" onclick="openDetail('${payload}')">
                  <i class="bi bi-arrow-right-short"></i> View
                </button>
              </div>
            </div>
          </div>
        `;
      }).join("");
    }

    const modal = new bootstrap.Modal(document.getElementById("detailModal"));

    function openDetail(encoded){
      const b = JSON.parse(decodeURIComponent(encoded));

      document.getElementById("m_title").innerHTML =
        `<i class="bi bi-sparkles"></i> ${escapeHtml(b.name_th)} <span style="color:var(--text-muted); font-size:0.85em; font-weight:400;">(${escapeHtml(b.name_en)})</span>`;

      const img = document.getElementById("m_img");
      const ph = document.getElementById("m_img_ph");

      if(b.image_url){
        img.src = b.image_url;
        img.style.display = "block";
        ph.style.display = "none";
      }else{
        img.src = "";
        img.style.display = "none";
        ph.style.display = "flex";
      }

      document.getElementById("m_desc").textContent = b.description || "-";
      document.getElementById("m_char").textContent = b.characteristics || "-";
      document.getElementById("m_care").textContent = b.care_instructions || "-";

      modal.show();
    }

    function applyFilter(){
      const q = (elQ.value || "").trim().toLowerCase();
      if(!q){
        render(allData);
        return;
      }
      const filtered = allData.filter(b => {
        const a = (b.name_th || "").toLowerCase();
        const e = (b.name_en || "").toLowerCase();
        return a.includes(q) || e.includes(q);
      });
      render(filtered);
    }

    async function load(){
      try{
        const res = await fetch("api/breeds_list.php", { cache:"no-store" });
        const json = await res.json();

        if(json.error){
          elList.innerHTML = `<div style="grid-column: 1/-1;"><div class="empty-state"><i class="bi bi-exclamation-triangle"></i><p style="color:#ff6b6b;">${escapeHtml(json.error)}</p></div></div>`;
          elCount.textContent = "Error loading breeds";
          return;
        }

        allData = json.data || [];
        render(allData);

      }catch(err){
        console.error(err);
        elList.innerHTML = `<div style="grid-column: 1/-1;"><div class="empty-state"><i class="bi bi-exclamation-triangle"></i><p style="color:#ff6b6b;">Failed to load breeds (check console)</p></div></div>`;
        elCount.textContent = "Error loading breeds";
      }
    }

    elQ.addEventListener("input", applyFilter);

    load();
  </script>
</body>
</body>
</html>
