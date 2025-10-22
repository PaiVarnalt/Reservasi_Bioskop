<?php
include '../lib/koneksi.php';

$action = $_GET['action'] ?? '';

// === CREATE ===
if ($action == 'create') {
  $id_studio = $_POST['id_studio'];
  $nomor_kursi = $_POST['nomor_kursi'];

  $stmt = $conn->prepare("INSERT INTO kursi (id_studio, nomor_kursi) VALUES (?, ?)");
  $stmt->execute([$id_studio, $nomor_kursi]);

  header("Location: index.php?page=kursi");
  exit;
}

// === DELETE ===
if ($action == 'delete') {
  $id = $_GET['id'];
  $conn->prepare("DELETE FROM kursi WHERE id_kursi=?")->execute([$id]);
  header("Location: index.php?page=kursi");
  exit;
}

// === UPDATE ===
if ($action == 'update') {
  $id_kursi = $_POST['id_kursi'];
  $id_studio = $_POST['id_studio'];
  $nomor_kursi = $_POST['nomor_kursi'];

  $stmt = $conn->prepare("UPDATE kursi SET id_studio=?, nomor_kursi=? WHERE id_kursi=?");
  $stmt->execute([$id_studio, $nomor_kursi, $id_kursi]);

  header("Location: index.php?page=kursi");
  exit;
}
?>

<!-- ====== UI ====== -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Manajemen Kursi</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg"></i> Tambah Kursi
  </button>
</div>

<!-- 🔍 Pencarian -->
<form method="GET" class="mb-3">
  <input type="hidden" name="page" value="kursi">
  <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan studio atau nomor kursi..."
           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button class="btn btn-dark" type="submit">Cari</button>
    <?php if (!empty($_GET['search'])): ?>
      <a href="index.php?page=kursi" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
  </div>
</form>

<table class="table table-bordered table-hover align-middle">
  <thead class="table-dark">
    <tr>
      <th>ID Kursi</th>
      <th>Studio</th>
      <th>Nomor Kursi</th>
      <th width="120">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $search = $_GET['search'] ?? '';

    if ($search) {
      $stmt = $conn->prepare("
        SELECT k.*, s.nama_studio 
        FROM kursi k 
        JOIN studio s ON k.id_studio = s.id_studio
        WHERE s.nama_studio LIKE :s OR k.nomor_kursi LIKE :s
        ORDER BY k.id_kursi DESC
      ");
      $stmt->execute(['s' => "%$search%"]);
    } else {
      $stmt = $conn->query("
        SELECT k.*, s.nama_studio 
        FROM kursi k 
        JOIN studio s ON k.id_studio = s.id_studio
        ORDER BY k.id_kursi DESC
      ");
    }

    if ($stmt->rowCount() == 0): ?>
      <tr><td colspan="4" class="text-center text-muted">Tidak ada data ditemukan</td></tr>
    <?php else:
      foreach ($stmt as $row): ?>
      <tr>
        <td><?= $row['id_kursi'] ?></td>
        <td><?= htmlspecialchars($row['nama_studio']) ?></td>
        <td><?= htmlspecialchars($row['nomor_kursi']) ?></td>
        <td>
          <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id_kursi'] ?>">
            <i class="bi bi-pencil"></i>
          </button>
          <a href="index.php?page=kursi&action=delete&id=<?= $row['id_kursi'] ?>"
             onclick="return confirm('Yakin hapus kursi ini?')"
             class="btn btn-sm btn-danger">
            <i class="bi bi-trash"></i>
          </a>
        </td>
      </tr>

      <!-- Modal Edit -->
      <div class="modal fade" id="edit<?= $row['id_kursi'] ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST" action="index.php?page=kursi&action=update">
              <div class="modal-header">
                <h5>Edit Kursi</h5>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_kursi" value="<?= $row['id_kursi'] ?>">
                <div class="mb-3">
                  <label>Studio</label>
                  <select name="id_studio" class="form-select" required>
                    <option value="">-- Pilih Studio --</option>
                    <?php
                    $studios = $conn->query("SELECT * FROM studio ORDER BY nama_studio ASC");
                    foreach ($studios as $studio):
                      $selected = $studio['id_studio'] == $row['id_studio'] ? 'selected' : '';
                      echo "<option value='{$studio['id_studio']}' $selected>{$studio['nama_studio']}</option>";
                    endforeach;
                    ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label>Nomor Kursi</label>
                  <input type="text" name="nomor_kursi" class="form-control" value="<?= htmlspecialchars($row['nomor_kursi']) ?>" required>
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
      <form method="POST" action="index.php?page=kursi&action=create">
        <div class="modal-header"><h5>Tambah Kursi</h5></div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Studio</label>
            <select name="id_studio" class="form-select" required>
              <option value="">-- Pilih Studio --</option>
              <?php
              $studios = $conn->query("SELECT * FROM studio ORDER BY nama_studio ASC");
              foreach ($studios as $studio):
                echo "<option value='{$studio['id_studio']}'>{$studio['nama_studio']}</option>";
              endforeach;
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Nomor Kursi</label>
            <input type="text" name="nomor_kursi" class="form-control" placeholder="Contoh: A1, B5, C10" required>
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
