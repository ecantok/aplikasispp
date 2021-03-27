<?php
require_once 'app.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session) {
  header("Location:index.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'navbar.php'; ?>
<div class="container">
    <h2>History Pembayaran Spp</h2>
    <div>
      <?php 
        $app->pesanDialog();
      ?>
        
    </div>
    <div style="display: none;">
      <button id="tampilModal">Tambah Data Kelas</button>
    </div>
    <?php if(!$app->cekPemissionLevel($levelUser,"Siswa")): ?>
    <form method="get">
      <div class="flex">
        <div>
          <label for="NIS"><b>NIS</b></label>
          <?php $nis = (!empty($_GET['nis'])&& $_GET['nis'] != '')? $_GET['nis']: ''; ?>
          <input type="text" name="nis" id="nis" value="<?=$nis?>">
        </div>

        <input style="margin: 15px 10px;" type="submit" value="Cari">
      </div>
    </form>
    <hr>
    <?php
    endif;
    $verifikasi = (!empty($_GET['nis'])&& $_GET['nis'] != '');
    $nis = null;
      if ($verifikasi) {
        $nis=$_GET['nis'];
      } else {
        $verifikasi = $levelUser == "Siswa";
        $nis = $idUser;
      }  
      if ($verifikasi) {
        $stmtSiswa = $conn->prepare("SELECT tbsiswa.*, tbkelas.*, tbspp.* FROM tbsiswa JOIN tbkelas ON tbsiswa.Kodekelas = tbkelas.KodeKelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE NIS = ?");
        $stmtSiswa->bind_param("s",$nis);
        $stmtSiswa->execute();
        $resultSiswa = $stmtSiswa->get_result();
        $dataSiswa = $resultSiswa->fetch_assoc();

        // $stmtSiswa = $conn->prepare("SELECT * FROM tbpembayaran WHERE nis = ?");
        // $stmtSiswa->bind_param("s",$_GET['nis']);
        // $stmtSiswa->execute();
        // $resultSiswa = $stmtSiswa->get_result();
        // $data 
        if ($resultSiswa->num_rows != 0) {
    ?>
  <h3>Biodata Siswa</h3>
  <table>
      <tr>
        <td>NIS</td>
        <td>:</td>
        <td><?= $dataSiswa['NIS'] ?></td>
      </tr>
      <tr>
        <td>Nama Siswa</td>
        <td>:</td>
        <td><?= $dataSiswa['NamaSiswa'] ?></td>
      </tr>
      <tr>
        <td>Kelas</td>
        <td>:</td>
        <td><?= $dataSiswa['NamaKelas'] ?></td>
      </tr>
      <tr>
        <td>Tahun Ajaran</td>
        <td>:</td>
        <td><?= $dataSiswa['TahunAjaran'] ?></td>
      </tr>
      <tr>
        <td>Jumlah Bayaran</td>
        <td>:</td>
        <td><?= "Rp.".$app->numberformat($dataSiswa['BesarBayaran']) ?></td>
      </tr>
  </table>
  <hr>
  <h3>Tagihan SPP Siswa</h3>
  <div style="overflow-x: auto;">
    <table class="table-view">
      <thead>
        <th>No.</th>
        <th>Kode Pembayaran</th>
        <th>Petugas</th>
        <th>Tgl. Pembayaran</th>
        <th>Bulan Dibayar</th>
        <th>Tahun Dibayar</th>
        <th>Status</th>
      </thead>
      <tbody>
        
        <?php $stmtSpp = $conn->prepare("SELECT tbpembayaran.*, tbpetugas.* FROM tbpembayaran JOIN tbpetugas ON tbpembayaran.KodePetugas = tbpetugas.KodePetugas WHERE NIS = ? ORDER BY `tbpembayaran`.`TahunDibayar` ASC");
        $stmtSpp->bind_param("s",$nis);
        $stmtSpp->execute();
        $resultSPP = $stmtSpp->get_result();
        $no = 1;
        while ($dataSPP = $resultSPP->fetch_assoc()) {
          ?>
          <tr>
            <td><?=$no?></td>
            <td><?=$dataSPP['KodePembayaran']?></td>
            <td><?=$dataSPP['NamaPetugas']?></td>
            <td><?=$dataSPP['TglPembayaran']?></td>
            <td><?=$dataSPP['BulanDibayar']?></td>
            <td><?=$dataSPP['TahunDibayar']?></td>
            <td style="text-align: center;"><?=$dataSPP['StatusPembayaran']?></td>
            
          </tr>
          <?php
          $no++;
        }
        ?>
        
      </tbody>
    </table>
  </div>
  <?php } else {
    echo "<p>Siswa yang dicari tidak ditemukan.</p> ";
  }} 
  $get = "";
  if ($verifikasi) {
    $get = "?nis=".$nis;
  }
  ?>
  <p><i>Histroy SPP hanya bisa melihat data SPP dengan NIS <?=($app->cekPemissionLevel($levelUser,'Siswa'))? "Anda":"yang dicari. Jika ingin melakukan entri pembayaran bisa dilakukan di halaman <a href='pembayaran.php{$get}'> berikut</a>";?></i></p>
  </div>
  <?php require_once "footer.php";?>
</body>
<script src="script.js"></script>
</html>