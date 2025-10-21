<?php
$page = $_GET['page'] ?? '';

switch ($page) {
  case 'users':
    include 'modul/users.php';
    break;
  case 'film':
    include 'modul/film.php';
    break;
  case 'jadwal_tayang':
    include 'modul/jadwal_tayang.php';
    break;
  case 'studio':
    include 'modul/studio.php';
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
