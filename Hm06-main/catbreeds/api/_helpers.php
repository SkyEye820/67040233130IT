<?php
function json_response($data, $status=200){
  http_response_code($status);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit;
}
function require_method($method){
  if($_SERVER["REQUEST_METHOD"] !== $method){
    json_response(["error"=>"Method not allowed"], 405);
  }
}
