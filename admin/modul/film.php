<?php
include '../lib/koneksi.php'; 

$action = $_GET['action'] ?? '';

// === CREATE ===
if ($action == 'create') {
  $judul = $_POST['judul'];
  $genre = $_POST['genre'];
  $durasi = $_POST['durasi'];
  $tanggal_rilis = $_POST['tanggal_rilis'];
  $status = $_POST['status'];
  $harga = $_POST['harga'];
  $gambar = '';
  $banner = '';

  // Upload gambar
  if (!empty($_FILES['gambar']['name'])) {
    $targetDir = '../asset/img/film/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $fileName = basename($_FILES['gambar']['name']);
    $filePath = $targetDir . $fileName;
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $filePath)) {
      $gambar = 'asset/img/film/' . $fileName;
    }
  }

  // Upload banner
  if (!empty($_FILES['banner']['name'])) {
    $targetDir = '../asset/img/banner/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $fileName = basename($_FILES['banner']['name']);
    $filePath = $targetDir . $fileName;
    if (move_uploaded_file($_FILES['banner']['tmp_name'], $filePath)) {
      $banner = 'asset/img/banner/' . $fileName;
    }
  }

  $stmt = $conn->prepare("INSERT INTO film (judul, genre, durasi, tanggal_rilis, status, harga, gambar, banner)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->execute([$judul, $genre, $durasi, $tanggal_rilis, $status, $harga, $gambar, $banner]);
  header("Location: index.php?page=film");
  exit;
}

// === DELETE ===
if ($action == 'delete') {
  $id = $_GET['id'];
  $conn->prepare("DELETE FROM film WHERE id_film=?")->execute([$id]);
  header("Location: index.php?page=film");
  exit;
}

// === UPDATE ===
if ($action == 'update') {
  $id = $_POST['id_film'];
  $judul = $_POST['judul'];
  $genre = $_POST['genre'];
  $durasi = $_POST['durasi'];
  $tanggal_rilis = $_POST['tanggal_rilis'];
  $status = $_POST['status'];
  $harga = $_POST['harga'];
  $gambar = $_POST['gambar_lama'];
  $banner = $_POST['banner_lama'];

  // Gambar baru
  if (!empty($_FILES['gambar']['name'])) {
    $targetDir = '../asset/img/film/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $fileName = basename($_FILES['gambar']['name']);
    $filePath = $targetDir . $fileName;
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $filePath)) {
      $gambar = 'asset/img/film/' . $fileName;
    }
  }

  // Banner baru
  if (!empty($_FILES['banner']['name'])) {
    $targetDir = '../asset/img/banner/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    $fileName = basename($_FILES['banner']['name']);
    $filePath = $targetDir . $fileName;
    if (move_uploaded_file($_FILES['banner']['tmp_name'], $filePath)) {
      $banner = 'asset/img/banner/' . $fileName;
    }
  }

  $stmt = $conn->prepare("UPDATE film 
                          SET judul=?, genre=?, durasi=?, tanggal_rilis=?, status=?, harga=?, gambar=?, banner=?
                          WHERE id_film=?");
  $stmt->execute([$judul, $genre, $durasi, $tanggal_rilis, $status, $harga, $gambar, $banner, $id]);
  header("Location: index.php?page=film");
  exit;
}
?>

<!-- ====== UI ====== -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Manajemen Film</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg"></i> Tambah Film
  </button>
</div>

<!-- 🔍 Form Pencarian -->
<form method="GET" class="mb-3">
  <input type="hidden" name="page" value="film">
  <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Cari Judul, Genre, atau Status..."
           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button class="btn btn-dark" type="submit">Cari</button>
    <?php if (!empty($_GET['search'])): ?>
      <a href="index.php?page=film" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
  </div>
</form>

<table class="table table-bordered table-hover align-middle">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Gambar</th>
      <th>Judul</th>
      <th>Genre</th>
      <th>Durasi</th>
      <th>Tanggal Rilis</th>
      <th>Status</th>
      <th>Harga</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $search = $_GET['search'] ?? '';
    if ($search) {
      $stmt = $conn->prepare("
        SELECT * FROM film 
        WHERE judul LIKE :search 
           OR genre LIKE :search 
           OR status LIKE :search
        ORDER BY id_film DESC
      ");
      $stmt->execute(['search' => "%$search%"]);
    } else {
      $stmt = $conn->query("SELECT * FROM film ORDER BY id_film DESC");
    }

    if ($stmt->rowCount() == 0): ?>
      <tr><td colspan="9" class="text-center text-muted">Tidak ada data ditemukan</td></tr>
    <?php else:
      foreach ($stmt as $row): ?>
      <tr>
        <td><?= $row['id_film'] ?></td>
        <td>
          <?php if (!empty($row['gambar'])): ?>
            <img src="../<?= $row['gambar'] ?>" width="60" height="80" style="object-fit: cover;">
          <?php else: ?>
            <span class="text-muted">-</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['judul']) ?></td>
        <td><?= htmlspecialchars($row['genre']) ?></td>
        <td><?= htmlspecialchars($row['durasi']) ?></td>
        <td><?= htmlspecialchars($row['tanggal_rilis']) ?></td>
        <td>
          <span class="badge bg-<?= 
            $row['status'] == 'tayang' ? 'success' : 
            ($row['status'] == 'coming' ? 'info' : 
            ($row['status'] == 'baru' ? 'primary' : 'secondary')) ?>">
            <?= $row['status'] ?>
          </span>
        </td>
        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
        <td>
          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id_film'] ?>">
            <i class="bi bi-pencil"></i>
          </button>
          <a href="index.php?page=film&action=delete&id=<?= $row['id_film'] ?>"
             onclick="return confirm('Yakin hapus data ini?')"
             class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="edit<?= $row['id_film'] ?>">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="index.php?page=film&action=update">
              <div class="modal-header"><h5>Edit Film</h5></div>
              <div class="modal-body">
                <input type="hidden" name="id_film" value="<?= $row['id_film'] ?>">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control" value="<?= $row['judul'] ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label>Genre</label>
                    <input type="text" name="genre" class="form-control" value="<?= $row['genre'] ?>" required>
                  </div>
                  <div class="col-md-4">
                    <label>Durasi</label>
                    <input type="text" name="durasi" class="form-control" value="<?= $row['durasi'] ?>">
                  </div>
                  <div class="col-md-4">
                    <label>Tanggal Rilis</label>
                    <input type="date" name="tanggal_rilis" class="form-control" value="<?= $row['tanggal_rilis'] ?>">
                  </div>
                  <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-select">
                      <option value="tayang" <?= $row['status']=='tayang'?'selected':'' ?>>Tayang</option>
                      <option value="populer" <?= $row['status']=='populer'?'selected':'' ?>>Populer</option>
                      <option value="baru" <?= $row['status']=='baru'?'selected':'' ?>>Baru</option>
                      <option value="coming" <?= $row['status']=='coming'?'selected':'' ?>>Coming</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Harga</label>
                    <input type="number" step="0.01" name="harga" class="form-control" value="<?= $row['harga'] ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                    <input type="hidden" name="gambar_lama" value="<?= $row['gambar'] ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Banner</label>
                    <input type="file" name="banner" class="form-control">
                    <input type="hidden" name="banner_lama" value="<?= $row['banner'] ?>">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success" type="submit">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data" action="index.php?page=film&action=create">
        <div class="modal-header"><h5>Tambah Film</h5></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Judul</label>
              <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Genre</label>
              <input type="text" name="genre" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label>Durasi</label>
              <input type="text" name="durasi" class="form-control">
            </div>
            <div class="col-md-4">
              <label>Tanggal Rilis</label>
              <input type="date" name="tanggal_rilis" class="form-control">
            </div>
            <div class="col-md-4">
              <label>Status</label>
              <select name="status" class="form-select">
                <option value="tayang">Tayang</option>
                <option value="populer">Populer</option>
                <option value="baru">Baru</option>
                <option value="coming">Coming</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Harga</label>
              <input type="number" step="0.01" name="harga" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Gambar</label>
              <input type="file" name="gambar" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Banner</label>
              <input type="file" name="banner" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary" type="submit">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>
