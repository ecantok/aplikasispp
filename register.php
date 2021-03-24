<?php require_once 'app.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Daftar User</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-box">
    <div class="flex">
    </div>
    <header>
      <h1>Halaman Daftar User</h1>
    </header>
    <form class="formbox" action="prosesregister.php" method="post">
      <div style="color: red; margin: 10px auto 20px;">
        <?php pesan();?>
      </div>
      <div>
        <label for="nis"><b>NIS</b></label>
        <input type="text" name="nis" placeholder="Masukkan NIS" required>
      </div>
      <div>
        <label for="namalengkap"><b>Nama Lengkap</b></label>
        <input type="text" name="namalengkap" placeholder="Masukkan Nama Lengkap" required>
      </div>
      <div>
        <label for="alamat"><b>Alamat</b></label>
        <input type="text" name="alamat" placeholder="Masukkan Alamat" required>
      </div>
      <div>
        <label for="telp"><b>Telp</b></label>
        <input type="text" name="telp" placeholder="Masukkan Telp" required>
      </div>
      <input class="reset" type="reset" value="Batal">
      <div class="middle">
        <input type="submit" value="Daftar">
          <a class="link" href="index.php" style="float:right; margin-top: 15px;">Login</a>
      </div>
    </form>
  </div>
</body>
</html>