<?php
require __DIR__."/db.php";
require __DIR__."/_helpers.php";
require __DIR__."/_config.php";

require_method("POST");

$name_th = trim($_POST["name_th"] ?? "");
$name_en = trim($_POST["name_en"] ?? "");
$description = trim($_POST["description"] ?? "");
$characteristics = trim($_POST["characteristics"] ?? "");
$care_instructions = trim($_POST["care_instructions"] ?? "");
$is_visible = isset($_POST["is_visible"]) ? 1 : 0;

if ($name_th === "" || $name_en === "" || $description === "") {
  json_response(["error" => "กรอก ชื่อไทย/ชื่ออังกฤษ/คำอธิบาย ให้ครบ"], 400);
}

$image_url = null;

// ถ้ามีรูปค่อยอัปโหลด
if (!empty($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {

  if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
    json_response(["error" => "อัปโหลดรูปไม่สำเร็จ (upload error code: ".$_FILES["image"]["error"].")"], 400);
  }

  $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
  $allow = ["jpg","jpeg","png","gif","webp"];
  if (!in_array($ext, $allow, true)) {
    json_response(["error"=>"ไฟล์รูปต้องเป็น jpg/png/gif/webp"], 400);
  }

  if (!is_dir(UPLOAD_DIR_ABS)) {
    @mkdir(UPLOAD_DIR_ABS, 0777, true);
  }

  // ถ้าโฟลเดอร์เขียนไม่ได้ ให้ “ไม่พังทั้งงาน” แต่ให้เพิ่มได้แบบไม่มีรูป
  if (!is_writable(UPLOAD_DIR_ABS)) {
    // จะให้ fail ก็ได้ แต่ตอนนี้ทำแบบ graceful
    $image_url = null;
  } else {
    $filename = "cat_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    $dest = UPLOAD_DIR_ABS . "/" . $filename;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $dest)) {
      json_response(["error" => "ย้ายไฟล์อัปโหลดไม่สำเร็จ (permission?)"], 500);
    }
    $image_url = UPLOAD_DIR_REL . "/" . $filename;
  }
}

$stmt = $conn->prepare("
  INSERT INTO CatBreeds (name_th, name_en, image_url, description, characteristics, care_instructions, is_visible)
  VALUES (?,?,?,?,?,?,?)
");
$stmt->bind_param("ssssssi", $name_th, $name_en, $image_url, $description, $characteristics, $care_instructions, $is_visible);
$stmt->execute();

json_response(["message" => "Created", "data" => ["id" => $conn->insert_id, "image_url" => $image_url]]);
