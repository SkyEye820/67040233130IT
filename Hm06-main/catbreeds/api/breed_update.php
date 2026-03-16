<?php
require __DIR__."/db.php";
require __DIR__."/_helpers.php";

require_method("POST");

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
if ($id <= 0) json_response(["error" => "Invalid id"], 400);

$stmt = $conn->prepare("SELECT image_url FROM CatBreeds WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$old = $stmt->get_result()->fetch_assoc();
if (!$old) json_response(["error" => "Not found"], 404);

$name_th = trim($_POST["name_th"] ?? "");
$name_en = trim($_POST["name_en"] ?? "");
$description = trim($_POST["description"] ?? "");
$characteristics = trim($_POST["characteristics"] ?? "");
$care_instructions = trim($_POST["care_instructions"] ?? "");
$is_visible = isset($_POST["is_visible"]) ? 1 : 0;

if ($name_th === "" || $name_en === "" || $description === "") {
  json_response(["error" => "กรอก ชื่อไทย/ชื่ออังกฤษ/คำอธิบาย ให้ครบ"], 400);
}

$image_url = $old["image_url"] ?? null;

if (!empty($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
  $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
  $allow = ["jpg","jpeg","png","gif","webp"];
  if (!in_array($ext, $allow, true)) {
    json_response(["error" => "ไฟล์รูปต้องเป็น jpg/png/gif/webp"], 400);
  }

  $uploadsDir = __DIR__ . "/../uploads";
  if (!is_dir($uploadsDir)) @mkdir($uploadsDir, 0777, true);

  $filename = "cat_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
  $dest = $uploadsDir . "/" . $filename;

  if (!move_uploaded_file($_FILES["image"]["tmp_name"], $dest)) {
    json_response(["error" => "อัปโหลดรูปไม่สำเร็จ"], 500);
  }

  if (!empty($image_url)) {
    $oldPath = __DIR__ . "/../" . $image_url;
    if (is_file($oldPath)) @unlink($oldPath);
  }

  $image_url = "uploads/" . $filename;
}

$up = $conn->prepare("
  UPDATE CatBreeds
  SET name_th=?, name_en=?, image_url=?, description=?, characteristics=?, care_instructions=?, is_visible=?
  WHERE id=?
");
$up->bind_param(
  "ssssssii",
  $name_th,
  $name_en,
  $image_url,
  $description,
  $characteristics,
  $care_instructions,
  $is_visible,
  $id
);
$up->execute();

json_response(["message" => "Updated", "data" => ["id" => $id]]);
