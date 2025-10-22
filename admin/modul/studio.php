<?php
include '../lib/koneksi.php';

$action = $_GET['action'] ?? '';

// === CREATE ===
if ($action == 'create') {
  $nama_studio = $_POST['nama_studio'];
  $tipe_layar = $_POST['tipe_layar'];
  $keterangan = $_POST['keterangan'];

  $stmt = $conn->prepare("INSERT INTO studio (nama_studio, tipe_layar, keterangan) VALUES (?, ?, ?)");
  $stmt->execute([$nama_studio, $tipe_layar, $keterangan]);

  header("Location: index.php?page=studio");
  exit;
}

// === DELETE ===
if ($action == 'delete') {
  $id = $_GET['id'];
  $conn->prepare("DELETE FROM studio WHERE id_studio=?")->execute([$id]);
  header("Location: index.php?page=studio");
  exit;
}

// === UPDATE ===
if ($action == 'update') {
  $id_studio = $_POST['id_studio'];
  $nama_studio = $_POST['nama_studio'];
  $tipe_layar = $_POST['tipe_layar'];
  $keterangan = $_POST['keterangan'];

  $stmt = $conn->prepare("UPDATE studio SET nama_studio=?, tipe_layar=?, keterangan=? WHERE id_studio=?");
  $stmt->execute([$nama_studio, $tipe_layar, $keterangan, $id_studio]);

  header("Location: index.php?page=studio");
  exit;
}
?>

<!-- ====== UI ====== -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Manajemen Studio</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg"></i> Tambah Studio
  </button>
</div>

<!-- 🔍 Pencarian -->
<form method="GET" class="mb-3">
  <input type="hidden" name="page" value="studio">
  <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Cari nama studio atau tipe layar..."
           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button class="btn btn-dark" type="submit">Cari</button>
    <?php if (!empty($_GET['search'])): ?>
      <a href="index.php?page=studio" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
  </div>
</form>

<table class="table table-bordered table-hover align-middle">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Nama Studio</th>
      <th>Tipe Layar</th>
      <th>Keterangan</th>
      <th width="120">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $search = $_GET['search'] ?? '';

    if ($search) {
      $stmt = $conn->prepare("SELECT * FROM studio WHERE nama_studio LIKE :s OR tipe_layar LIKE :s ORDER BY id_studio DESC");
      $stmt->execute(['s' => "%$search%"]);
    } else {
      $stmt = $conn->query("SELECT * FROM studio ORDER BY id_studio DESC");
    }

    if ($stmt->rowCount() == 0): ?>
      <tr><td colspan="5" class="text-center text-muted">Tidak ada data ditemukan</td></tr>
    <?php else:
      foreach ($stmt as $row): ?>
      <tr>
        <td><?= $row['id_studio'] ?></td>
        <td><?= htmlspecialchars($row['nama_studio']) ?></td>
        <td><?= htmlspecialchars($row['tipe_layar'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
        <td>
          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id_studio'] ?>">
            <i class="bi bi-pencil"></i>
          </button>
          <a href="index.php?page=studio&action=delete&id=<?= $row['id_studio'] ?>"
             onclick="return confirm('Yakin hapus studio ini?')"
             class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="edit<?= $row['id_studio'] ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST" action="index.php?page=studio&action=update">
              <div class="modal-header">
                <h5>Edit Studio</h5>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_studio" value="<?= $row['id_studio'] ?>">
                <div class="mb-3">
                  <label>Nama Studio</label>
                  <input type="text" name="nama_studio" class="form-control" value="<?= htmlspecialchars($row['nama_studio']) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Tipe Layar</label>
                  <input type="text" name="tipe_layar" class="form-control" value="<?= htmlspecialchars($row['tipe_layar']) ?>">
                </div>
                <div class="mb-3">
                  <label>Keterangan</label>
                  <textarea name="keterangan" class="form-control" rows="3"><?= htmlspecialchars($row['keterangan']) ?></textarea>
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
      <form method="POST" action="index.php?page=studio&action=create">
        <div class="modal-header"><h5>Tambah Studio</h5></div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Studio</label>
            <input type="text" name="nama_studio" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Tipe Layar</label>
            <input type="text" name="tipe_layar" class="form-control" placeholder="Contoh: 2D / 3D / IMAX">
          </div>
          <div class="mb-3">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
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
