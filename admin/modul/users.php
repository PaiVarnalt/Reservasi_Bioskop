<h2>Daftar User</h2>
<input type="text" id="searchUser" placeholder="Cari user...">
<div id="userTable">
  <?php
  $stmt = $conn->query("SELECT * FROM user ORDER BY id_user DESC");
  echo "<table border='1' cellpadding='8'>";
  echo "<tr><th>ID</th><th>Nama</th><th>Email</th></tr>";
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>{$row['id_user']}</td><td>{$row['nama']}</td><td>{$row['email']}</td></tr>";
  }
  echo "</table>";
  ?>
</div>
