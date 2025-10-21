 <style>
    body {
      background-color: #303030;
      color: white;
    }
    .navbar {
      background-color: #1c1c1c;
    }
    .banner {
      background-image: url('https://link-to-your-banner.jpg'); /* Ganti dengan URL gambar banner */
      background-size: cover;
      height: 300px;
      position: relative;
      margin-bottom: 30px;
    }
    .film-card img {
      height: 300px;
      object-fit: cover;
    }
    .search-bar input {
      border-radius: 30px;
    }
  </style>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark px-4">
    <a class="navbar-brand" href="#">LOGO</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Film</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Tiket</a></li>
      </ul>
    </div>
  </nav>

  <!-- Banner -->
  <div class="banner d-flex align-items-end text-white p-4">
    <h2>JUNJI ITO: Rumah Misteri</h2>
  </div>

  <!-- Film Section -->
  <div class="container">
    <h3>FILM</h3>

    <!-- Search -->
    <div class="search-bar my-4">
      <input type="text" class="form-control" placeholder="Mau nonton apa, nih?">
    </div>

    <!-- Film Cards -->
    <div class="row row-cols-2 row-cols-md-4 g-4">
      <!-- Card 1 -->
      <div class="col">
        <div class="card film-card bg-dark text-white">
          <img src="https://link-to-poster1.jpg" class="card-img-top" alt="Film 1">
          <div class="card-body">
            <h6 class="card-title">Film Title 1</h6>
            <small class="text-muted">2D | 13+</small>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col">
        <div class="card film-card bg-dark text-white">
          <img src="https://link-to-poster2.jpg" class="card-img-top" alt="Film 2">
          <div class="card-body">
            <h6 class="card-title">Film Title 2</h6>
            <small class="text-muted">2D | SU</small>
          </div>
        </div>
      </div>

      <!-- Tambahkan lebih banyak card sesuai kebutuhan -->
    </div>
  </div>
