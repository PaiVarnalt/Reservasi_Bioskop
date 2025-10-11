<?php include 'lib/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Bioskop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-light">
  <?php include 'sidebar.php'; ?>

  <div class="container mt-4">
    <div id="content">
      <?php include 'modul/dashboard.php'; ?>
    </div>
  </div>

  <script>
    // Ganti konten dengan AJAX
    $(document).ready(function(){
      $('a[data-page]').on('click', function(e){
        e.preventDefault();
        const page = $(this).data('page');
        $('#content').load('modul/' + page + '.php');
        $('a.nav-link').removeClass('active');
        $(this).addClass('active');
      });
    });
  </script>
</body>
</html>
