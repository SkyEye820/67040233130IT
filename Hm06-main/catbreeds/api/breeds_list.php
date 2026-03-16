<?php
require __DIR__."/db.php";
require __DIR__."/_helpers.php";

require_method("GET");

// ถ้า ?all=1 -> เอาทั้งหมด (แอดมิน)
// ถ้าไม่ส่ง -> เอาเฉพาะเผยแพร่ (หน้าบ้าน)
$all = isset($_GET["all"]) && $_GET["all"] == "1";

$q = trim($_GET["q"] ?? "");

$sql = "SELECT id, name_th, name_en, image_url, description, characteristics, care_instructions, is_visible
        FROM CatBreeds";
$conds = [];
$params = [];
$types = "";

// filter visible สำหรับหน้าบ้าน
if (!$all) {
  $conds[] = "is_visible = 1";
}

// search
if ($q !== "") {
  $conds[] = "(name_th LIKE ? OR name_en LIKE ?)";
  $like = "%".$q."%";
  $params[] = $like;
  $params[] = $like;
  $types .= "ss";
}

if (count($conds) > 0) {
  $sql .= " WHERE " . implode(" AND ", $conds);
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (count($params) > 0) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();

$res = $stmt->get_result();
$data = [];
while($row = $res->fetch_assoc()){
  $data[] = $row;
}

json_response(["data" => $data]);
