<?php
header("Content-Type: text/plain; charset=utf-8");
$dir = __DIR__ . "/uploads";
echo "dir: $dir\n";

if (!is_dir($dir)) {
  echo "❌ uploads folder not found\n";
  exit;
}

echo "is_writable: " . (is_writable($dir) ? "YES" : "NO") . "\n";

$test = $dir . "/_test_write.txt";
$r = @file_put_contents($test, "ok " . date("c"));
echo $r === false ? "❌ write failed\n" : "✅ write ok\n";
