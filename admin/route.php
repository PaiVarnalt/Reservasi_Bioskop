<?php
$page = $_GET['page'] ?? '';

switch ($page) {
  case 'users':
    include 'modul/users.php';
    break;

  default:
    echo "
      <div class='text-center mt-5'>
        <h2>Selamat Datang di Dashboard Admin 🎬</h2>
        <p class='text-muted'>Gunakan menu di sidebar untuk mengelola data.</p>
      </div>
    ";
    break;
}
?>
