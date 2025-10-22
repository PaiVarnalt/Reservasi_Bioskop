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
  <link rel="stylesheet" href="asset/css/style.css">
</head>
<body class="bg-light">

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-danger d-flex align-items-center" href="#">
      <img src="asset/img/logo.png" alt="Logo" width="50" class="">
      BioskopKu
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="modul/film/film.php">Film</a></li>
        <li class="nav-item"><a class="nav-link" href="modul/tiket/tiket.php">Tiket Anda</a></li>

        <?php if (isset($_SESSION['user'])) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" data-bs-toggle="dropdown">
              <img src="uploads/profile/<?php echo $_SESSION['user']['foto_profil']; ?>" 
                   class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
              <?php echo $_SESSION['user']['username']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="modul/user/profil.php">Profil Saya</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php } else { ?>
          <li class="nav-item"><a class="nav-link btn btn-danger text-white px-3 ms-2" href="login.php">Login</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ================= BANNER ================= -->
<section class="mt-5 pt-5">
  <div id="carouselBanner" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="asset/img/banner1.jpg" class="d-block w-100" alt="Banner 1">
        <div class="carousel-caption d-none d-md-block bg-white bg-opacity-75 rounded-4 p-3">
          <h2 class="fw-bold text-danger">Nikmati Pengalaman Nonton Terbaik 🎥</h2>
          <p class="text-dark">Pesan tiket film favoritmu hanya di BioskopKu</p>
          <a href="modul/film/film.php" class="btn btn-danger">Lihat Film</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="asset/img/banner2.jpg" class="d-block w-100" alt="Banner 2">
        <div class="carousel-caption d-none d-md-block bg-white bg-opacity-75 rounded-4 p-3">
          <h2 class="fw-bold text-danger">Film Terbaru Tiap Minggu!</h2>
          <p class="text-dark">Jangan lewatkan tayangan perdana yang seru!</p>
        </div>
      </div>
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
          <img src="uploads/img/<?php echo $film['gambar']; ?>" class="card-img-top" alt="<?php echo $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="card-title fw-bold text-dark"><?php echo $film['judul']; ?></h6>
            <p class="text-muted small mb-2"><?php echo $film['genre']; ?> • <?php echo $film['durasi']; ?> min</p>
            <a href="modul/film/detail_film.php?id=<?php echo $film['film_id']; ?>" class="btn btn-outline-danger btn-sm">Lihat Detail</a>
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
          <img src="uploads/img/<?php echo $film['gambar']; ?>" class="card-img-top" alt="<?php echo $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="fw-bold"><?php echo $film['judul']; ?></h6>
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
          <img src="uploads/img/<?php echo $film['gambar']; ?>" class="card-img-top" alt="<?php echo $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="fw-bold"><?php echo $film['judul']; ?></h6>
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
          <img src="uploads/img/<?php echo $film['gambar']; ?>" class="card-img-top" alt="<?php echo $film['judul']; ?>">
          <div class="card-body text-center">
            <h6 class="fw-bold"><?php echo $film['judul']; ?></h6>
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
