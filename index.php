<?php
session_start();
include 'lib/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BioskopKu - Nonton Seru Setiap Hari!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="asset/css/style.css">
</head>
<body class="bg-light">

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-danger d-flex align-items-center" href="index.php">
      <img src="asset/img/logo.png" alt="Logo" width="45" class="me-2">
      BioskopKu
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active fw-bold' : '' ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="modul/film/film.php">Film</a></li>
        <li class="nav-item"><a class="nav-link" href="modul/tiket/tiket.php">Tiket Anda</a></li>

        <?php if (isset($_SESSION['user'])) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" data-bs-toggle="dropdown">
              <img src="uploads/profile/<?= $_SESSION['user']['foto_profil']; ?>" 
                   class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
              <?= $_SESSION['user']['username']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="modul/user/profil.php"><i class="bi bi-person-circle me-2"></i>Profil Saya</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </li>
        <?php } else { ?>
          <li class="nav-item"><a class="nav-link btn btn-danger text-white px-3 ms-2" href="login.php">Login</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ================= BANNER DINAMIS ================= -->
<section class="mt-5 pt-5">
  <div id="carouselBanner" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

      <?php
      $stmt = $conn->query("SELECT * FROM film WHERE status='tayang' ORDER BY tanggal_rilis DESC LIMIT 5");
      $active = true;
      while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
        <div class="carousel-item <?= $active ? 'active' : ''; ?>">
          <img src="<?= $film['gambar']; ?>" class="d-block w-100" alt="<?= $film['judul']; ?>">
          
        </div>
      <?php
        $active = false;
      }
      ?>

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</section>

<!-- ================= FILM SEDANG TAYANG ================= -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-4 fw-bold text-danger">Sedang Tayang</h2>
    <div class="row g-4">
      <?php
      $stmt = $conn->query("SELECT * FROM film WHERE status='tayang' ORDER BY tanggal_rilis DESC LIMIT 8");
      while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="<?= $film['gambar']; ?>" class="card-img-top" alt="<?= $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="card-title fw-bold text-dark"><?= $film['judul']; ?></h6>
            <p class="text-muted small mb-2"><?= $film['genre']; ?> • <?= $film['durasi']; ?> min</p>
            <a href="modul/film/detail_film.php?id=<?= $film['film_id']; ?>" class="btn btn-outline-danger btn-sm">Lihat Detail</a>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>

<!-- ================= FILM POPULER ================= -->
<section class="py-5 bg-white">
  <div class="container">
    <h2 class="text-center mb-4 fw-bold text-danger">Film Populer</h2>
    <div class="row g-4">
      <?php
      $stmt = $conn->query("SELECT * FROM film WHERE status='populer' LIMIT 4");
      while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="<?= $film['gambar']; ?>" class="card-img-top" alt="<?= $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="card-title fw-bold text-dark"><?= $film['judul']; ?></h6>
            <p class="text-muted small mb-2"><?= $film['genre']; ?> • <?= $film['durasi']; ?> min</p>
            <a href="modul/film/detail_film.php?id=<?= $film['film_id']; ?>" class="btn btn-outline-danger btn-sm">Lihat Detail</a>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>

<!-- ================= FILM BARU ================= -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-4 fw-bold text-danger">Film Baru</h2>
    <div class="row g-4">
      <?php
      $stmt = $conn->query("SELECT * FROM film WHERE status='baru' LIMIT 4");
      while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="<?= $film['gambar']; ?>" class="card-img-top" alt="<?= $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="card-title fw-bold text-dark"><?= $film['judul']; ?></h6>
            <p class="text-muted small mb-2"><?= $film['genre']; ?> • <?= $film['durasi']; ?> min</p>
            <a href="modul/film/detail_film.php?id=<?= $film['film_id']; ?>" class="btn btn-outline-danger btn-sm">Lihat Detail</a>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>

<!-- ================= COMING SOON ================= -->
<section class="py-5 bg-white">
  <div class="container">
    <h2 class="text-center mb-4 fw-bold text-danger">Coming Soon</h2>
    <div class="row g-4">
      <?php
      $stmt = $conn->query("SELECT * FROM film WHERE status='coming' LIMIT 4");
      while ($film = $stmt->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
          <img src="<?= $film['gambar']; ?>" class="card-img-top" alt="<?= $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="card-title fw-bold text-dark"><?= $film['judul']; ?></h6>
            <p class="text-muted small mb-2"><?= $film['genre']; ?> • <?= $film['durasi']; ?> min</p>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer class="bg-white text-center py-4 border-top">
  <p class="mb-0 text-muted">© 2025 <span class="text-danger fw-bold">BioskopKu</span> | Nonton Seru Setiap Hari 🍿</p>
  <small>Hubungi kami: <a href="mailto:info@bioskopku.com" class="text-danger text-decoration-none">info@bioskopku.com</a></small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
