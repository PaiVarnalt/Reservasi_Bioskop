<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <?php include 'sidebar.php'; ?>
    <div class="main-content" id="main-content">
      <?php include 'content.php'; ?>
    </div>
  </div>

  <script src="assets/js/main.js"></script>
</body>
</html>
