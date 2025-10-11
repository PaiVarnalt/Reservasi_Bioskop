<h2>Manajemen User 👥</h2>

<div style="margin-bottom: 15px;">
  <input type="text" id="searchUser" placeholder="Cari user..." style="padding:8px;width:250px;">
  <button id="addUserBtn">Tambah User</button>
</div>

<div id="userTable"></div>

<div id="userModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:#000000a8;align-items:center;justify-content:center;">
  <div style="background:white;padding:20px;width:400px;border-radius:10px;position:relative;">
    <h3 id="modalTitle"></h3>
    <form id="userForm" enctype="multipart/form-data">
      <input type="hidden" name="user_id" id="user_id">

      <label>Username:</label><br>
      <input type="text" name="username" id="username" required><br><br>

      <label>Email:</label><br>
      <input type="email" name="email" id="email" required><br><br>

      <label>Password:</label><br>
      <input type="password" name="password" id="password"><br><br>

      <label>Foto Profil:</label><br>
      <input type="file" name="foto_profil" accept="image/*"><br><br>

      <label>Role:</label><br>
      <select name="role" id="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select><br><br>

      <button type="submit">Simpan</button>
      <button type="button" id="closeModal">Batal</button>
    </form>
  </div>
</div>

<script>
function loadUsers(keyword = '') {
  fetch(`actions/user_action.php?action=read&keyword=${keyword}`)
    .then(res => res.text())
    .then(html => document.getElementById('userTable').innerHTML = html);
}

document.getElementById('searchUser').addEventListener('keyup', e => {
  loadUsers(e.target.value);
});

document.getElementById('addUserBtn').onclick = () => {
  document.getElementById('modalTitle').innerText = 'Tambah User';
  document.getElementById('userForm').reset();
  document.getElementById('user_id').value = '';
  document.getElementById('userModal').style.display = 'flex';
};

document.getElementById('closeModal').onclick = () => {
  document.getElementById('userModal').style.display = 'none';
};

document.getElementById('userForm').onsubmit = e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  fetch('actions/user_action.php?action=save', { method:'POST', body:formData })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      document.getElementById('userModal').style.display = 'none';
      loadUsers();
    });
};

loadUsers();
</script>
