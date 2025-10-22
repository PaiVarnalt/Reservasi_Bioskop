<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar vh-100 position-fixed">
  <div class="position-sticky pt-3">
    <h5 class="text-white px-3 mb-3">🎬 Admin Panel</h5>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == '' ? 'active fw-bold' : '' ?>" href="index.php">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == 'users' ? 'active fw-bold' : '' ?>" href="index.php?page=users">
          <i class="bi bi-person-lines-fill"></i> Manajemen User
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == 'film' ? 'active fw-bold' : '' ?>" href="index.php?page=film">
          <i class="bi bi-film"></i> Manajemen Film
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == 'studio' ? 'active fw-bold' : '' ?>" href="index.php?page=studio">
          <i class="bi bi-building"></i> Manajemen Studio
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == 'jadwal_tayang' ? 'active fw-bold' : '' ?>" href="index.php?page=jadwal_tayang">
          <i class="bi bi-calendar-event"></i> Jadwal Tayang
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == 'kursi' ? 'active fw-bold' : '' ?>" href="index.php?page=kursi">
          <i class="bi bi-grid-3x3-gap"></i> Manajemen Kursi
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == 'tiket' ? 'active fw-bold' : '' ?>" href="index.php?page=tiket">
          <i class="bi bi-ticket-perforated"></i> Data Reservasi
        </a>
      </li>
    </ul>
  </div>
</nav>
