<?php
require __DIR__."/db.php";
require __DIR__."/_helpers.php";

require_method("POST");

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
// รับทั้ง "0"/"1" และ 0/1
$is_visible = isset($_POST["is_visible"]) ? (int)$_POST["is_visible"] : -1;

if ($id <= 0 || ($is_visible !== 0 && $is_visible !== 1)) {
  json_response(["error" => "Invalid id/is_visible"], 400);
}

$stmt = $conn->prepare("UPDATE CatBreeds SET is_visible=? WHERE id=?");
$stmt->bind_param("ii", $is_visible, $id);
$stmt->execute();

json_response([
  "message" => "Updated",
  "data" => ["id" => $id, "is_visible" => $is_visible]
]);
