<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$file = "modules/$page.php";
if (file_exists($file)) {
  include $file;
} else {
  echo "<h3>Halaman tidak ditemukan</h3>";
}
?>
