<?php
require __DIR__."/db.php";
require __DIR__."/_helpers.php";

require_method("POST");

$id = (int)($_POST["id"] ?? 0);
if($id<=0) json_response(["error"=>"Invalid id"], 400);

// ลบรูปใน uploads (ถ้ามี)
$stmt = $conn->prepare("SELECT image_url FROM CatBreeds WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if(!$row) json_response(["error"=>"Not found"], 404);

$image_url = $row["image_url"] ?? "";
if($image_url){
  $path = __DIR__ . "/../" . $image_url;
  if(is_file($path)) @unlink($path);
}

// ลบ record
$del = $conn->prepare("DELETE FROM CatBreeds WHERE id=?");
$del->bind_param("i", $id);
$del->execute();

json_response(["message"=>"Deleted", "data"=>["id"=>$id]]);
