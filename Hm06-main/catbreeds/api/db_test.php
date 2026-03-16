<?php
header("Content-Type: text/plain; charset=utf-8");

require __DIR__ . "/_config.php";

echo "DB_HOST = " . DB_HOST . PHP_EOL;
echo "DB_USER = " . DB_USER . PHP_EOL;
echo "DB_NAME = " . DB_NAME . PHP_EOL;
echo "DB_PASS = " . (DB_PASS === "" ? "(empty)" : "(set)") . PHP_EOL;
echo "----" . PHP_EOL;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $conn->set_charset("utf8mb4");
  echo "✅ CONNECT OK" . PHP_EOL;

  $r = $conn->query("SHOW TABLES");
  echo "Tables:" . PHP_EOL;
  while($row = $r->fetch_array()){
    echo "- " . $row[0] . PHP_EOL;
  }

}catch(Throwable $e){
  echo "❌ CONNECT FAIL" . PHP_EOL;
  echo "Message: " . $e->getMessage() . PHP_EOL;
}
