<?php

  require_once 'app.php';
  if (!$session) {
    header("Location:login.php");
  }
  if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $namasekolah = $_POST['namasekolah'];
    $npsn = $_POST['npsn'];
    $alamat = $_POST['alamat'];
    $kelurahan = $_POST['kelurahan'];
    $kecamatan = $_POST['kecamatan'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $kodepos = $_POST['kodepos'];
    $notelp = $_POST['notelp'];
    $email = $_POST['email'];
    $newImageName = $_POST['oldimage'];

    //Upload gambar
    if (!empty($_FILES['gambar']['name'])) {

      $nama_foto = $_FILES['gambar']['name'];
      $ukuran_foto = $_FILES['gambar']['size'];
      $imageTmp = $_FILES['gambar']['tmp_name'];

      $extensionName = array('jpg', 'jpeg', 'png');
      $x = explode('.', $nama_foto);
      $extension = strtolower(end($x));
      $newImageName = date('dmYHis') . $nama_foto;

      if (in_array($extension, $extensionName)) {

        if ($ukuran_foto < 2000001) {

          if (move_uploaded_file($imageTmp, "img/".$newImageName)) {
            $resultGambar = $conn->query("SELECT gambar_logo FROM tbdatasekolah");
            $datagambar = $resultGambar->fetch_assoc();
            $gambar_logo = $datagambar['gambar_logo'];

            if ($gambar_logo != "default.png") {
              if (is_file("img/".$gambar_logo)) 
                unlink("img/".$gambar_logo);
            }

          } else {
            $app->setpesan('Gambar gagal disimpan!', '',"red");
            exit();
          }
          
        } else {
          $app->setpesan('Ukuran gambar terlalu besar!', '',"red");
          exit();
        }
        
      } else {
        $app->setpesan('Ekstensi file tidak diperbolehkan!', '',"red");
        exit();
      }
    }
    //UPDATE
    $q = "UPDATE `tbdatasekolah` SET 
    `gambar_logo`= ?,
    `nama_sekolah`= ?,
    `npsn`= ?,
    `alamat`= ?,
    `kelurahan`= ?,
    `kecamatan`= ?,
    `kota`= ?,
    `provinsi`= ?,
    `kode_pos`= ?,
    `no_telp`= ?,
    `email`= ?";
    $stmtUpdate = $conn->prepare($q);
    $stmtUpdate->bind_param("ssisssssiss", $newImageName, $namasekolah, $npsn, $alamat, $kelurahan, $kecamatan, $kota, $provinsi, $kodepos, $notelp, $email);
    $stmtUpdate ->execute();
    if ($conn->errno == 0) {
      $app->setpesan("Data Sekolah Berhasil disimpan!");
    } else {
      $app->setpesan("Data Sekolah Berhasil disimpan!","","red");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Sekolah | Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include_once 'navbar.php';?>
  <div class="container">
    <h2>Data Sekolah</h2>
    <div>
      <?php $app->pesanDialog(); $app->pesanDirect();
      $data = $conn->query("SELECT * FROM tbdatasekolah")->fetch_assoc();
      ?>
    </div>
    <div class="card-box">
      <div class="card-body">
        <table class="table-data">
          <tr>
            <td colspan="2" style="text-align: center;">
            <img  class="img" src="<?=BASEURL."/img/".$data["gambar_logo"] ?>" alt="Gambar logo tidak ada">
            </td>
          </tr>
          <tr>
            <td style="width: 200px;">Nama Sekolah</td>
            <td><?= $data["nama_sekolah"] ?></td>
          </tr>
          <tr>
            <td>NPSN</td>
            <td><?= $data["npsn"] ?></td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td><?= $data["alamat"] ?></td>
          </tr>
          <tr>
            <td>Kelurahan</td>
            <td><?= $data["kelurahan"] ?></td>
          </tr>
          <tr>
            <td>Kecamatan</td>
            <td><?= $data["kecamatan"] ?></td>
          </tr>
          <tr>
            <td>Kota</td>
            <td><?= $data["kota"] ?></td>
          </tr>
          <tr>
            <td>Provinsi</td>
            <td><?= $data["provinsi"] ?></td>
          </tr>
          <tr>
            <td>Kode POS</td>
            <td><?= $data["kode_pos"] ?></td>
          </tr>
          <tr>
            <td>Nomor Telepon</td>
            <td><?= $data["no_telp"] ?></td>
          </tr>
          <tr>
            <td>Email</td>
            <td><?= $data["email"] ?></td>
          </tr>
        </table>
      </div>
      <div class="middle">
        <button class="form-button" id="tampilModal">Edit Data Sekolah</button>
      </div>
    </div>
    <!-- MODAL BOX -->
    <div class="modal remove-top" id="modalBox">
  
      <div class="modal-content-medium">
        <span class="close">&times;</span>
        <h4><span id="modal-title">Edit Data Sekolah</span> </h4>
        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" name="oldimage" value="<?= $data['gambar_logo'] ?>">
          <p>Logo Sekolah :</p>
          <br>
          <div class="middle">
            <img src="<?= BASEURL."/img/".$data['gambar_logo'] ?>" alt="Gambar Obat" class="img-fluid" width="150px" height="150px">

          </div>
          <div class="middle">
            <label for="gambar" class="form-button">Edit Gambar </label>
            <input type="file" style="display: none;" name="gambar" id="gambar" class="input-file"> </input>
          </div>
          <br>
          <br>
          <label for="namasekolah">Nama Sekolah : </label>
          <input type="text" name="namasekolah" id="namasekolah"  value="<?=$data["nama_sekolah"] ?>">
          <label for="npsn">NPSN : </label>
          <input type="number" name="npsn" id="npsn" value="<?=$data["npsn"] ?>">
          <label for="alamat">Alamat : </label>
          <input type="text" name="alamat" id="alamat" value="<?=$data["alamat"] ?>">
          <label for="kelurahan">Kelurahan : </label>
          <input type="text" name="kelurahan" id="kelurahan" value="<?=$data["kelurahan"] ?>">
          <label for="kecamatan">Kecamatan : </label>
          <input type="text" name="kecamatan" id="kecamatan" value="<?=$data["kecamatan"] ?>">
          <label for="kota">Kota : </label>
          <input type="text" name="kota" id="kota" value="<?= $data["kota"] ?>">
          <label for="provinsi">Provinsi : </label>
          <input type="text" name="provinsi" id="provinsi" value="<?=$data["provinsi"] ?>">
          <label for="kodepos">Kode Pos : </label>
          <input type="text" name="kodepos" id="kodepos" value="<?=$data["kode_pos"] ?>">
          <label for="notelp">Nomor Telepon : </label>
          <input type="text" name="notelp" id="notelp" value="<?=$data["no_telp"] ?>">
          <label for="notelp">Email : </label>
          <input type="email" name="email" id="email" value="<?=$data["email"] ?>">
          <br>
          <input type="submit" class="button" value="Edit">
        </form>
      </div>
    </div>
  </div>
  <?php require_once "footer.php" ?>
  <script>
    const btn = document.getElementById("tampilModal");
    const modal = document.getElementById("modalBox");
    btn.onclick = function () {
      modal.style.display="block";
    };
    const span = document.getElementsByClassName("close")[0];
    span.onclick = function () {
      modal.style.display="none";
    }
    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = "none";
      } 
    }
  </script>
  <script src="navbar.js"></script>
</body>
</html>