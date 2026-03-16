<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Fantasy PHP Profile & Loop (New Design)</title>

<style>
    /* ---------------- GOOGLE FONTS & GLOBAL STYLES ---------------- */
    @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap');

    * {
        box-sizing: border-box;
    }

    body{
        font-family: "Prompt", "Poppins", sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #0f0415 0%, #1a0d2e 25%, #2d0f4a 50%, #1a0d2e 75%, #0f0415 100%);
        background-size: 400% 400%;
        animation: spaceMove 20s infinite alternate ease-in-out;
        color: #e0e0e0;
        min-height: 100vh;
    }

    @keyframes spaceMove{
        0%{background-position: 0% 0%;}
        100%{background-position: 100% 100%;}
    }

    .container{
        max-width: 950px;
        margin: auto;
        padding: 50px 20px 100px;
    }

    .section{
        background: rgba(20, 8, 35, 0.7);
        backdrop-filter: blur(10px);
        padding: 40px;
        border-radius: 25px;
        margin-bottom: 35px;
        border: 2px solid rgba(147, 51, 234, 0.4);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        animation: slideUp .6s ease;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .section:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 80px rgba(147, 51, 234, 0.3);
        border-color: rgba(147, 51, 234, 0.6);
    }

    @keyframes slideUp{
        from{opacity: 0; transform: translateY(20px);}
        to{opacity: 1; transform: translateY(0);}
    }

    h1{
        margin-top: 0;
        margin-bottom: 25px;
        font-size: 32px;
        background: linear-gradient(135deg, #bb86fc, #9c27b0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    h2, h3{
        color: #bb86fc;
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 12px;
    }

    h3 {
        font-size: 16px;
        color: #ce93d8;
    }

    p {
        line-height: 1.8;
        color: #d0d0d0;
        margin: 8px 0;
    }

    b {
        color: #bb86fc;
        font-weight: 600;
    }

    /* ---------------- PROFILE SECTION ---------------- */
    .profile-box{
        display: flex;
        gap: 35px;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .profile-box img{
        width: 220px;
        height: 220px;
        border-radius: 20px;
        object-fit: cover;
        border: 4px solid #bb86fc;
        box-shadow: 0 15px 40px rgba(187, 134, 252, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        flex-shrink: 0;
    }

    .profile-box img:hover {
        transform: scale(1.05) rotate(2deg);
        box-shadow: 0 20px 50px rgba(187, 134, 252, 0.5);
    }

    .profile-info {
        flex: 1;
        min-width: 280px;
    }

    .profile-info p {
        font-size: 16px;
    }

    .contact {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .contact a{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: white;
        padding: 12px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg, #9333ea, #7c3aed);
        border: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(147, 51, 234, 0.4);
        cursor: pointer;
    }

    .contact a:hover{
        background: linear-gradient(135deg, #bb86fc, #ce93d8);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(187, 134, 252, 0.6);
    }

    .contact img{
        width: 18px;
        height: 18px;
        filter: brightness(0) invert(1);
    }

    /* ---------------- ACCORDION STYLES ---------------- */
    .accordion-item{
        margin-bottom: 12px;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #5e35b1;
        transition: all 0.3s ease;
    }

    .accordion-item:hover {
        border-color: #bb86fc;
        box-shadow: 0 5px 15px rgba(187, 134, 252, 0.2);
    }

    .accordion-title{
        width: 100%;
        text-align: left;
        background: linear-gradient(135deg, rgba(156, 39, 176, 0.15), rgba(123, 31, 162, 0.15));
        padding: 18px 24px;
        color: #bb86fc;
        font-size: 18px;
        cursor: pointer;
        border: none;
        outline: none;
        transition: all .3s ease;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .accordion-title::after {
        content: "▼";
        font-size: 12px;
        transition: transform 0.3s ease;
        color: #ce93d8;
    }

    .accordion-item.active .accordion-title::after {
        transform: rotate(180deg);
    }

    .accordion-title:hover{
        background: linear-gradient(135deg, rgba(187, 134, 252, 0.15), rgba(206, 147, 216, 0.15));
        color: #ce93d8;
    }

    .accordion-content{
        display: none;
        padding: 25px;
        background: rgba(40, 15, 70, 0.5);
        border-top: 2px solid #5e35b1;
        animation: slideDown 0.3s ease;
    }

    .accordion-content.show {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loop Section Styles */
    .loop-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .output-section {
        background: linear-gradient(135deg, rgba(156, 39, 176, 0.1), rgba(123, 31, 162, 0.1));
        padding: 20px;
        border-radius: 14px;
        border: 2px solid rgba(187, 134, 252, 0.3);
    }

    .code-section {
        background: linear-gradient(135deg, rgba(51, 51, 102, 0.1), rgba(76, 76, 127, 0.1));
        padding: 20px;
        border-radius: 14px;
        border: 2px solid rgba(147, 51, 234, 0.3);
    }

    .output-section h3 {
        color: #ce93d8;
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(206, 147, 216, 0.5);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .code-section h3 {
        color: #9c27b0;
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(156, 39, 176, 0.5);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Accordion items styling per loop type */
    .accordion-item.for-loop {
        border-color: #bb86fc;
    }

    .accordion-item.for-loop .accordion-title {
        background: linear-gradient(135deg, rgba(187, 134, 252, 0.15), rgba(206, 147, 216, 0.1));
    }

    .accordion-item.while-loop {
        border-color: #ba68c8;
    }

    .accordion-item.while-loop .accordion-title {
        background: linear-gradient(135deg, rgba(186, 104, 200, 0.15), rgba(206, 147, 216, 0.1));
    }

    .accordion-item.do-while-loop {
        border-color: #ab47bc;
    }

    .accordion-item.do-while-loop .accordion-title {
        background: linear-gradient(135deg, rgba(171, 71, 188, 0.15), rgba(206, 147, 216, 0.1));
    }

    pre{
        background: linear-gradient(135deg, #0a0515 0%, #15051e 100%);
        padding: 20px;
        border-radius: 14px;
        border: 1px solid rgba(187, 134, 252, 0.2);
        overflow-x: auto;
        font-size: 13px;
        color: #00ff88;
        white-space: pre-wrap;
        word-wrap: break-word;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.5);
        font-family: 'Courier New', monospace;
        line-height: 1.5;
        margin: 10px 0;
    }

    /* Scrollbar styling */
    pre::-webkit-scrollbar {
        height: 8px;
    }

    pre::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
    }

    pre::-webkit-scrollbar-thumb {
        background: #9333ea;
        border-radius: 10px;
    }

    pre::-webkit-scrollbar-thumb:hover {
        background: #bb86fc;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        h1 {
            font-size: 26px;
        }

        .profile-box {
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .profile-box img {
            width: 180px;
            height: 180px;
        }

        .section {
            padding: 25px;
        }

        .contact {
            justify-content: center;
        }

        .contact a {
            font-size: 13px;
            padding: 10px 14px;
        }

        .loop-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
</style>
</head>

<body>

<div class="container">

<?php
// ===========================
// PHP ข้อมูลนักศึกษา
// ===========================
$university = "มหาวิทยาลัยราชภัฏอุดรธานี";
$faculty = "คณะวิทยาศาสตร์";
$major = "สาขาเทคโนโลยีสารสนเทศ";
$fullname = "นนทวัฒน์ นาเวียง";
$nickname = "ฟ้า";
$student_id = "67040233130";
$intro = "ฟังเพลง เล่นเกม ดูหนัง  เล่นกีฬา ";
$picture = "profile_image.jpg";

// Social icons
$icon_fb = "https://cdn-icons-png.flaticon.com/512/124/124010.png";
$icon_ig = "https://cdn-icons-png.flaticon.com/512/2111/2111463.png";
$icon_email = "https://cdn-icons-png.flaticon.com/512/732/732200.png";

$facebook = "https://www.facebook.com/fha.iiar";
$instagram = "https://www.instagram.com/faaainga/";
$email = "67040233130@udru.ac.th";
?>

<!-- =========================== PROFILE =========================== -->
<div class="section">
    <h1>👤 ข้อมูลนักศึกษา</h1>

    <div class="profile-box">

        <!-- รูป -->
        <img src="<?= $picture ?>" alt="Profile">

        <!-- รายละเอียด -->
        <div class="profile-info">
            <p><b>🎓 มหาวิทยาลัย:</b> <?= $university ?></p>
            <p><b>📚 คณะ:</b> <?= $faculty ?></p>
            <p><b>🔬 สาขา:</b> <?= $major ?></p>
            <p><b>👨 ชื่อ–นามสกุล:</b> <?= $fullname ?></p>
            <p><b>😎 ชื่อเล่น:</b> <?= $nickname ?></p>
            <p><b>🔑 รหัสนักศึกษา:</b> <?= $student_id ?></p>
            <p><b>🎵 งานอดิเรก:</b> <?= $intro ?></p>

            <div class="contact">
                <a href="<?= $facebook ?>" target="_blank"><img src="<?= $icon_fb ?>">Facebook</a>
                <a href="<?= $instagram ?>" target="_blank"><img src="<?= $icon_ig ?>">Instagram</a>
                <a href="mailto:<?= $email ?>"><img src="<?= $icon_email ?>">67040233130@udru.ac.th</a>
            </div>
        </div>
    </div>
</div>


<!-- =========================== LOOP MENU =========================== -->
<div class="section">
    <h1>🔄 งาน LOOP ทั้งหมด</h1>

    <!-- ------------ FOR ------------ -->
    <div class="accordion-item for-loop">
        <button class="accordion-title">➡️ Loop FOR</button>
        <div class="accordion-content">
            <div class="loop-grid">
                <div class="output-section">
                    <h3>📊 ผลลัพธ์:</h3>
                    <pre>
<?php
echo "=== 1. สามเหลี่ยม * ===\n";
for($i=1;$i<=4;$i++){ echo str_repeat("*",$i)."\n"; }

echo "\n=== 2. สี่เหลี่ยมตัวเลขแนวนอน ===\n";
for($i=1;$i<=3;$i++){ echo str_repeat($i." ",4)."\n"; }

echo "\n=== 3. สามเหลี่ยมตัวเลข ===\n";
for($i=1;$i<=3;$i++){ echo str_repeat($i." ",$i)."\n"; }

echo "\n=== 4. สี่เหลี่ยมขอบ ===\n";
for($i=0;$i<6;$i++){ echo str_repeat("* ",6)."\n"; }
for($i=1;$i<=3;$i++){ echo "* ".str_repeat($i." ",4)."*\n"; }
for($i=0;$i<1;$i++){ echo str_repeat("* ",6)."\n"; }

echo "\n=== 5. สามเหลี่ยมกลับหัว ===\n";
for($i=3;$i>=1;$i--){ echo str_repeat($i." ",$i)."\n"; }
?>
                    </pre>
                </div>
                <div class="code-section">
                    <h3>💻 โค้ด FOR:</h3>
                    <pre style="color: #FFD700;">
// 1. สามเหลี่ยม *
for($i=1;$i<=4;$i++) 
{ echo str_repeat("*",$i)."\n"; }

// 2. สี่เหลี่ยมตัวเลขแนวนอน
for($i=1;$i<=3;$i++) 
{ echo str_repeat($i." ",4)."\n"; }

// 3. สามเหลี่ยมตัวเลข
for($i=1;$i<=3;$i++) 
{ echo str_repeat($i." ",$i)."\n"; }

// 4. สี่เหลี่ยมขอบ
for($i=0;$i<6;$i++) 
{ echo str_repeat("* ",6)."\n"; }

// 5. สามเหลี่ยมกลับหัว
for($i=3;$i>=1;$i--) 
{ echo str_repeat($i." ",$i)."\n"; }
                    </pre>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------ WHILE ------------ -->
    <div class="accordion-item while-loop">
        <button class="accordion-title">🔁 Loop WHILE</button>
        <div class="accordion-content">
            <div class="loop-grid">
                <div class="output-section">
                    <h3>📊 ผลลัพธ์:</h3>
                    <pre>
<?php
echo "=== 1. สามเหลี่ยม * ===\n";
$i=1; while($i<=4){ echo str_repeat("*",$i)."\n"; $i++; }

echo "\n=== 2. สี่เหลี่ยมตัวเลขแนวนอน ===\n";
$i=1; while($i<=3){ echo str_repeat($i." ",4)."\n"; $i++; }

echo "\n=== 3. สามเหลี่ยมตัวเลข ===\n";
$i=1; while($i<=3){ echo str_repeat($i." ",$i)."\n"; $i++; }

echo "\n=== 4. สี่เหลี่ยมขอบ ===\n";
$i=0; while($i<6){ echo str_repeat("* ",6)."\n"; $i++; }
$i=1; while($i<=3){ echo "* ".str_repeat($i." ",4)."*\n"; $i++; }
$i=0; while($i<1){ echo str_repeat("* ",6)."\n"; $i++; }

echo "\n=== 5. สามเหลี่ยมกลับหัว ===\n";
$i=3; while($i>=1){ echo str_repeat($i." ",$i)."\n"; $i--; }
?>
                    </pre>
                </div>
                <div class="code-section">
                    <h3>💻 โค้ด WHILE:</h3>
                    <pre style="color: #87CEEB;">
// 1. สามเหลี่ยม *
$i=1; 
while($i<=4) { 
    echo str_repeat("*",$i)."\n"; 
    $i++; 
}

// 2. สี่เหลี่ยมตัวเลขแนวนอน
$i=1; 
while($i<=3) { 
    echo str_repeat($i." ",4)."\n"; 
    $i++; 
}

// 3. สามเหลี่ยมตัวเลข
$i=1; 
while($i<=3) { 
    echo str_repeat($i." ",$i)."\n"; 
    $i++; 
}

// 4. สี่เหลี่ยมขอบ
$i=0; 
while($i<6) { 
    echo str_repeat("* ",6)."\n"; 
    $i++; 
}

// 5. สามเหลี่ยมกลับหัว
$i=3; 
while($i>=1) { 
    echo str_repeat($i." ",$i)."\n"; 
    $i--; 
}
                    </pre>
                </div>
            </div>
        </div>
    </div>

    <!-- ------------ DO-WHILE ------------ -->
    <div class="accordion-item do-while-loop">
        <button class="accordion-title">🔄 Loop DO-WHILE</button>
        <div class="accordion-content">
            <div class="loop-grid">
                <div class="output-section">
                    <h3>📊 ผลลัพธ์:</h3>
                    <pre>
<?php
echo "=== 1. สามเหลี่ยม * ===\n";
$i=1; 
do{ echo str_repeat("*",$i)."\n"; $i++; }while($i<=4);

echo "\n=== 2. สี่เหลี่ยมตัวเลขแนวนอน ===\n";
$i=1;
do{ echo str_repeat($i." ",4)."\n"; $i++; }while($i<=3);

echo "\n=== 3. สามเหลี่ยมตัวเลข ===\n";
$i=1;
do{ echo str_repeat($i." ",$i)."\n"; $i++; }while($i<=3);

echo "\n=== 4. สี่เหลี่ยมขอบ ===\n";
$i=0;
do{ echo str_repeat("* ",6)."\n"; $i++; }while($i<6);
$i=1;
do{ echo "* ".str_repeat($i." ",4)."*\n"; $i++; }while($i<=3);
$i=0;
do{ echo str_repeat("* ",6)."\n"; $i++; }while($i<1);

echo "\n=== 5. สามเหลี่ยมกลับหัว ===\n";
$i=3;
do{ echo str_repeat($i." ",$i)."\n"; $i--; }while($i>=1);
?>
                    </pre>
                </div>
                <div class="code-section">
                    <h3>💻 โค้ด DO-WHILE:</h3>
                    <pre style="color: #FFB6C1;">
// 1. สามเหลี่ยม *
$i=1; 
do { 
    echo str_repeat("*",$i)."\n"; 
    $i++; 
} while($i<=4);

// 2. สี่เหลี่ยมตัวเลขแนวนอน
$i=1;
do { 
    echo str_repeat($i." ",4)."\n"; 
    $i++; 
} while($i<=3);

// 3. สามเหลี่ยมตัวเลข
$i=1;
do { 
    echo str_repeat($i." ",$i)."\n"; 
    $i++; 
} while($i<=3);

// 4. สี่เหลี่ยมขอบ
$i=0;
do { 
    echo str_repeat("* ",6)."\n"; 
    $i++; 
} while($i<6);

// 5. สามเหลี่ยมกลับหัว
$i=3;
do { 
    echo str_repeat($i." ",$i)."\n"; 
    $i--; 
} while($i>=1);
                    </pre>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<script>
    // ========== ระบบ accordion ==========
    const accordions = document.querySelectorAll(".accordion-title");

    accordions.forEach(btn => {
        btn.addEventListener("click", () => {
            const item = btn.parentElement;
            const content = btn.nextElementSibling;
            const isOpen = item.classList.contains("active");
            
            // ปิดทั้งหมดก่อน
            document.querySelectorAll(".accordion-item").forEach(el => {
                el.classList.remove("active");
                el.querySelector(".accordion-content").classList.remove("show");
            });

            // ถ้าไม่ได้เปิดอยู่ ให้เปิด
            if (!isOpen){
                item.classList.add("active");
                content.classList.add("show");
            }
        });
    });

    // เปิด accordion แรกโดยค่าเริ่มต้น
    const firstAccordion = document.querySelector(".accordion-item");
    if (firstAccordion) {
        firstAccordion.classList.add("active");
        firstAccordion.querySelector(".accordion-content").classList.add("show");
    }
</script>

</body>
</html>
