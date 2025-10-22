<?php
include '../lib/koneksi.php'; // pastikan koneksi dipanggil

$action = $_GET['action'] ?? '';

if ($action == 'create') {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $email = $_POST['email'];
  $role = $_POST['role'];
  $foto = '';

  // Upload foto baru
  if (!empty($_FILES['foto']['name'])) {
    $targetDir = '../asset/img/user/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fotoName = basename($_FILES['foto']['name']);
    $fotoPath = $targetDir . $fotoName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath)) {
      $foto = 'asset/img/user/' . $fotoName; // path untuk disimpan ke database
    }
  }

  $stmt = $conn->prepare("INSERT INTO user (username, password, email, foto_profil, role) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$username, $password, $email, $foto, $role]);

  header("Location: index.php?page=users");
  exit;
}

if ($action == 'delete') {
  $id = $_GET['id'];
  $conn->prepare("DELETE FROM user WHERE user_id=?")->execute([$id]);
  header("Location: index.php?page=users");
  exit;
}

if ($action == 'update') {
  $id = $_POST['user_id'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $foto = $_POST['foto_lama'];

  if (!empty($_FILES['foto']['name'])) {
    $targetDir = '../asset/img/user/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fotoName = basename($_FILES['foto']['name']);
    $fotoPath = $targetDir . $fotoName;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath)) {
      $foto = 'asset/img/user/' . $fotoName;
    }
  }

  $stmt = $conn->prepare("UPDATE user SET username=?, email=?, role=?, foto_profil=? WHERE user_id=?");
  $stmt->execute([$username, $email, $role, $foto, $id]);

  header("Location: index.php?page=users");
  exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>Manajemen User</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg"></i> Tambah User
  </button>
</div>

<table class="table table-bordered table-hover">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Foto</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $stmt = $conn->query("SELECT * FROM user ORDER BY user_id DESC");
    foreach ($stmt as $row):
    ?>
    <tr>
      <td><?= $row['user_id'] ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td>
        <span class="badge bg-<?= $row['role'] == 'admin' ? 'danger' : 'secondary' ?>">
          <?= $row['role'] ?>
        </span>
      </td>
      <td>
        <?php if (!empty($row['foto_profil'])): ?>
          <img src="../<?= $row['foto_profil'] ?>" width="50" height="50" class="rounded-circle" style="object-fit: cover;">
        <?php else: ?>
          <span class="text-muted">-</span>
        <?php endif; ?>
      </td>
      <td>
        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $row['user_id'] ?>">
          <i class="bi bi-pencil"></i>
        </button>
        <a href="index.php?page=users&action=delete&id=<?= $row['user_id'] ?>"
           onclick="return confirm('Yakin hapus data ini?')"
           class="btn btn-sm btn-danger">
          <i class="bi bi-trash"></i>
        </a>
      </td>
    </tr>

    <!-- Modal Edit -->
    <div class="modal fade" id="edit<?= $row['user_id'] ?>">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" enctype="multipart/form-data" action="index.php?page=users&action=update">
            <div class="modal-header">
              <h5>Edit User</h5>
            </div>
            <div class="modal-body">
              <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
              <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>" required>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
              </div>
              <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-select">
                  <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                  <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                </select>
              </div>
              <div class="mb-3">
                <label>Foto Profil</label>
                <input type="file" name="foto" class="form-control">
                <input type="hidden" name="foto_lama" value="<?= $row['foto_profil'] ?>">
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
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data" action="index.php?page=users&action=create">
        <div class="modal-header"><h5>Tambah User</h5></div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select">
              <option value="admin">Admin</option>
              <option value="user" selected>User</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Foto Profil</label>
            <input type="file" name="foto" class="form-control">
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
