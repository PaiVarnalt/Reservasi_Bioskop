<?php
include '../koneksi.php';
$action = $_GET['action'] ?? '';

if ($action == 'read') {
  $keyword = $_GET['keyword'] ?? '';
  $sql = "SELECT * FROM user 
          WHERE username LIKE :kw 
          OR email LIKE :kw 
          ORDER BY user_id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':kw' => "%$keyword%"]);

  echo "<table border='1' cellpadding='8' width='100%'>";
  echo "<tr>
          <th>ID</th>
          <th>Foto</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Tanggal Daftar</th>
          <th>Aksi</th>
        </tr>";
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $foto = !empty($row['foto_profil']) ? "../asset/user/".$row['foto_profil'] : "../asset/user/default.png";
    echo "<tr>
      <td>{$row['user_id']}</td>
      <td><img src='$foto' width='50' style='border-radius:8px;'></td>
      <td>{$row['username']}</td>
      <td>{$row['email']}</td>
      <td>{$row['role']}</td>
      <td>{$row['tanggal_daftar']}</td>
      <td>
        <button onclick=\"editUser({$row['user_id']}, '{$row['username']}', '{$row['email']}', '{$row['role']}')\">Edit</button>
        <button onclick=\"deleteUser({$row['user_id']})\">Hapus</button>
      </td>
    </tr>";
  }
  echo "</table>";

  echo "<script>
  function editUser(id, username, email, role){
    document.getElementById('modalTitle').innerText = 'Edit User';
    document.getElementById('user_id').value = id;
    document.getElementById('username').value = username;
    document.getElementById('email').value = email;
    document.getElementById('role').value = role;
    document.getElementById('password').value = '';
    document.getElementById('userModal').style.display = 'flex';
  }

  function deleteUser(id){
    if(confirm('Yakin ingin menghapus user ini?')){
      fetch('actions/user_action.php?action=delete&id='+id)
        .then(res=>res.text())
        .then(msg=>{
          alert(msg);
          loadUsers();
        });
    }
  }
  </script>";
}

// simpan data
elseif ($action == 'save') {
  $id = $_POST['user_id'] ?? '';
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'] ?? '';
  $role = $_POST['role'] ?? 'user';

  // upload foto
  $fotoName = '';
  if (!empty($_FILES['foto_profil']['name'])) {
    $ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
    $fotoName = uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['foto_profil']['tmp_name'], "../asset/user/" . $fotoName);
  }

  if ($id == '') {
    $stmt = $conn->prepare("INSERT INTO user (username, password, email, foto_profil, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email, $fotoName, $role]);
    echo "User berhasil ditambahkan";
  } else {
    if (!empty($password)) {
      $stmt = $conn->prepare("UPDATE user SET username=?, email=?, password=?, role=? WHERE user_id=?");
      $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role, $id]);
    } else {
      $stmt = $conn->prepare("UPDATE user SET username=?, email=?, role=? WHERE user_id=?");
      $stmt->execute([$username, $email, $role, $id]);
    }

    if ($fotoName != '') {
      $stmt = $conn->prepare("UPDATE user SET foto_profil=? WHERE user_id=?");
      $stmt->execute([$fotoName, $id]);
    }

    echo "User berhasil diperbarui";
  }
}

// hapus
elseif ($action == 'delete') {
  $id = $_GET['id'] ?? '';
  if ($id != '') {
    $stmt = $conn->prepare("DELETE FROM user WHERE user_id=?");
    $stmt->execute([$id]);
    echo "User berhasil dihapus";
  }
}
?>
