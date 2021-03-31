<?php
  require_once 'app.php';
  if (!$session) {
    header("Location:login.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Home | Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include_once 'navbar.php';?>
  <div class="container">
    <h1>Selamat datang di Aplikasi Pembayaran SPP!</h1>
  </div>
  <?php require_once "footer.php" ?>
</body>
</html>
<script src="navbar.js"></script>