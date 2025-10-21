<?php
include '../lib/koneksi.php';

$action = $_GET['action'] ?? '';

// === CREATE ===
if ($action == 'create') {
  $id_film = $_POST['id_film'];
  $tanggal = $_POST['tanggal'];
  $jam = $_POST['jam'];
  $id_studio = $_POST['id_studio'];

  $stmt = $conn->prepare("INSERT INTO jadwal_tayang (id_film, tanggal, jam, id_studio) VALUES (?, ?, ?, ?)");
  $stmt->execute([$id_film, $tanggal, $jam, $id_studio]);

  header("Location: index.php?page=jadwal_tayang");
  exit;
}

// === DELETE ===
if ($action == 'delete') {
  $id = $_GET['id'];
  $conn->prepare("DELETE FROM jadwal_tayang WHERE id_jadwal=?")->execute([$id]);
  header("Location: index.php?page=jadwal_tayang");
  exit;
}

// === UPDATE ===
if ($action == 'update') {
  $id = $_POST['id_jadwal'];
  $id_film = $_POST['id_film'];
  $tanggal = $_POST['tanggal'];
  $jam = $_POST['jam'];
  $id_studio = $_POST['id_studio'];

  $stmt = $conn->prepare("UPDATE jadwal_tayang SET id_film=?, tanggal=?, jam=?, id_studio=? WHERE id_jadwal=?");
  $stmt->execute([$id_film, $tanggal, $jam, $id_studio, $id]);

  header("Location: index.php?page=jadwal_tayang");
  exit;
}
?>

<!-- ====== UI ====== -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Manajemen Jadwal Tayang</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg"></i> Tambah Jadwal
  </button>
</div>

<!-- 🔍 Pencarian -->
<form method="GET" class="mb-3">
  <input type="hidden" name="page" value="jadwal_tayang">
  <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Cari film, studio, atau tanggal..."
           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button class="btn btn-dark" type="submit">Cari</button>
    <?php if (!empty($_GET['search'])): ?>
      <a href="index.php?page=jadwal_tayang" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
  </div>
</form>

<table class="table table-bordered table-hover align-middle">
  <thead class="table-dark">
    <tr>
      <th>ID Jadwal</th>
      <th>Judul Film</th>
      <th>Tanggal</th>
      <th>Jam</th>
      <th>Studio</th>
      <th width="120">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $search = $_GET['search'] ?? '';

    if ($search) {
      $stmt = $conn->prepare("
        SELECT j.*, f.judul, s.nama_studio 
        FROM jadwal_tayang j
        JOIN film f ON j.id_film = f.id_film
        JOIN studio s ON j.id_studio = s.id_studio
        WHERE f.judul LIKE :s OR s.nama_studio LIKE :s OR j.tanggal LIKE :s
        ORDER BY j.id_jadwal DESC
      ");
      $stmt->execute(['s' => "%$search%"]);
    } else {
      $stmt = $conn->query("
        SELECT j.*, f.judul, s.nama_studio 
        FROM jadwal_tayang j
        JOIN film f ON j.id_film = f.id_film
        JOIN studio s ON j.id_studio = s.id_studio
        ORDER BY j.id_jadwal DESC
      ");
    }

    if ($stmt->rowCount() == 0): ?>
      <tr><td colspan="6" class="text-center text-muted">Tidak ada data ditemukan</td></tr>
    <?php else:
      foreach ($stmt as $row): ?>
      <tr>
        <td><?= $row['id_jadwal'] ?></td>
        <td><?= htmlspecialchars($row['judul']) ?></td>
        <td><?= htmlspecialchars($row['tanggal']) ?></td>
        <td><?= htmlspecialchars(substr($row['jam'], 0, 5)) ?></td>
        <td><?= htmlspecialchars($row['nama_studio']) ?></td>
        <td>
          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id_jadwal'] ?>">
            <i class="bi bi-pencil"></i>
          </button>
          <a href="index.php?page=jadwal_tayang&action=delete&id=<?= $row['id_jadwal'] ?>"
             onclick="return confirm('Yakin hapus jadwal ini?')"
             class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="edit<?= $row['id_jadwal'] ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST" action="index.php?page=jadwal_tayang&action=update">
              <div class="modal-header"><h5>Edit Jadwal</h5></div>
              <div class="modal-body">
                <input type="hidden" name="id_jadwal" value="<?= $row['id_jadwal'] ?>">
                <div class="mb-3">
                  <label>Film</label>
                  <select name="id_film" class="form-select" required>
                    <?php
                    $films = $conn->query("SELECT * FROM film ORDER BY judul ASC")->fetchAll();
                    foreach ($films as $film):
                    ?>
                      <option value="<?= $film['id_film'] ?>" <?= $film['id_film'] == $row['id_film'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($film['judul']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label>Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" value="<?= $row['tanggal'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Jam</label>
                  <input type="time" name="jam" class="form-control" value="<?= $row['jam'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Studio</label>
                  <select name="id_studio" class="form-select" required>
                    <?php
                    $studios = $conn->query("SELECT * FROM studio ORDER BY nama_studio ASC")->fetchAll();
                    foreach ($studios as $studio):
                    ?>
                      <option value="<?= $studio['id_studio'] ?>" <?= $studio['id_studio'] == $row['id_studio'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($studio['nama_studio']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="index.php?page=jadwal_tayang&action=create">
        <div class="modal-header"><h5>Tambah Jadwal Tayang</h5></div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Film</label>
            <select name="id_film" class="form-select" required>
              <option value="" disabled selected>Pilih Film</option>
              <?php
              $films = $conn->query("SELECT * FROM film ORDER BY judul ASC")->fetchAll();
              foreach ($films as $film):
              ?>
                <option value="<?= $film['id_film'] ?>"><?= htmlspecialchars($film['judul']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Jam</label>
            <input type="time" name="jam" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Studio</label>
            <select name="id_studio" class="form-select" required>
              <option value="" disabled selected>Pilih Studio</option>
              <?php
              $studios = $conn->query("SELECT * FROM studio ORDER BY nama_studio ASC")->fetchAll();
              foreach ($studios as $studio):
              ?>
                <option value="<?= $studio['id_studio'] ?>"><?= htmlspecialchars($studio['nama_studio']) ?></option>
              <?php endforeach; ?>
            </select>
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
