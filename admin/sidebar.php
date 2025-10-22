<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar vh-100 position-fixed">
  <div class="position-sticky pt-3">
    <h5 class="text-white px-3 mb-3">Admin Panel</h5>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == '' ? 'active fw-bold' : '' ?>" href="index.php">
          <i class="bi bi-house"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white <?= ($_GET['page'] ?? '') == 'users' ? 'active fw-bold' : '' ?>" href="index.php?page=users">
          <i class="bi bi-people"></i> Manajemen User
        </a>
      </li>
    </ul>
  </div>
</nav>
