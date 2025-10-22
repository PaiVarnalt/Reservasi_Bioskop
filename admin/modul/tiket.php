<?php
include 'lib/koneksi.php'; // pastikan ini menghasilkan variabel $conn = new PDO(...);
?>

<div class="container mt-4">
  <h2 class="mb-3">Data Reservasi Tiket</h2>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Nama User</th>
        <th>Film</th>
        <th>Tanggal & Jam</th>
        <th>Studio</th>
        <th>Kursi</th>
        <th>Status</th>
        <th>Total Harga</th>
        <th>Tanggal Reservasi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = "
        SELECT r.*, 
               u.username, 
               f.judul AS film, 
               j.tanggal, j.jam, 
               s.nama_studio, 
               k.nomor_kursi
        FROM reservasi r
        JOIN user u ON r.id_user = u.user_id
        JOIN jadwal_tayang j ON r.id_jadwal = j.id_jadwal
        JOIN film f ON j.id_film = f.id_film
        JOIN studio s ON j.id_studio = s.id_studio
        JOIN kursi k ON r.id_kursi = k.id_kursi
        ORDER BY r.tanggal_reservasi DESC
      ";

      $stmt = $conn->prepare($query);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($result) > 0) {
        foreach ($result as $row) {
          echo "
          <tr>
            <td>{$no}</td>
            <td>{$row['username']}</td>
            <td>{$row['film']}</td>
            <td>{$row['tanggal']} {$row['jam']}</td>
            <td>{$row['nama_studio']}</td>
            <td>{$row['nomor_kursi']}</td>
            <td>
              <span class='badge " . 
              ($row['status'] == 'dibayar' ? 'bg-success' : 
              ($row['status'] == 'batal' ? 'bg-danger' : 'bg-warning text-dark')) . 
              "'>{$row['status']}</span>
            </td>
            <td>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>
            <td>{$row['tanggal_reservasi']}</td>
          </tr>";
          $no++;
        }
      } else {
        echo "<tr><td colspan='9' class='text-center'>Belum ada data reservasi.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
