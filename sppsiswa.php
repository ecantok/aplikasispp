<?php
require_once 'app.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session||$app->cekPemissionLevel($levelUser)===false) {
  header("Location:index.php");
  exit;
}
$parameter1 = (!empty($_GET['tahunajaran'])&& $_GET['tahunajaran'] != '');
$parameter2 = (!empty($_GET['kelas'])&& $_GET['kelas'] != '');
$selectedTahunAjaran = ($parameter1)? $_GET['tahunajaran']:'' ;
$kelas = ($parameter2)? $_GET['kelas']:'' ;
// $query = ("SELECT tbkelas.*, tbspp.TahunAjaran FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP");
// $resultKelas = $conn->query($query);
// $cekKelas = ($resultKelas -> num_rows == 0);
// if ($cekKelas) {
//   $app->setpesan("Mohon Masukan Data Kelas Terlebih Dahulu");
//   header("Location: kelas.php");
// }
$result = $conn->query("SELECT * FROM tbsiswa");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data SPP Siswa || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
  <?php $app->pesanDialog(); ?>
</head>
<body>
<?php  include_once 'navbar.php' ?>
  <div class="container">
    <h2>Data SPP Siswa</h2>
    
    <!-- FORM TAHUN AJARAN -->
    <div>
      <form method="get">
        <div class="flex">
          <div>
            <label for="tahunajaran"><b>Tahun Ajaran</b></label>
            <select id="tahunajaran" name="tahunajaran">
              <?php 
                $app->buatTahunAjaran($selectedTahunAjaran);
              ?>
            </select>
          </div>
          <input style="margin: 15px 10px;" type="submit" value="Lihat">
        </div>
      </form>
    </div>

    <hr>
    <div style="display: none;" class="mb">
      <button id="tampilModal" class="button">Tambah Data SPP Siswa</button>
    </div>

    <?php
      if (($parameter1&&!$parameter2)){
      if ($parameter1) {
        $stmtSpp = $conn->prepare("SELECT tbkelas.*, tbspp.* FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE tbspp.TahunAjaran = ?");
        $stmtSpp->bind_param("s",$selectedTahunAjaran);
        $stmtSpp->execute();
        $resultSpp = $stmtSpp->get_result();
        $dataSpp = $resultSpp->fetch_assoc();
        if ($resultSpp->num_rows != 0) {
    ?>
    <h2>Data Kelas</h2>
    <div id="tableId" style="overflow-x:auto;">
      <table class="table-view">
        <thead>
          <th>No.</th>
          <th>Tingkat</th>
          <th>Jurusan</th>
          <th>Nama Kelas</th>
          <th>Besar Bayaran</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php $i=1; while ($row = $resultSpp ->fetch_assoc() ):?>
            <tr>
              <td><?=$i ?></td>
              <td><?=$row['Tingkat']?></td>
              <td><?=$row['Jurusan'] ?></td>
              <td><?=$row['NamaKelas'] ?></td>
              <td><?=$row['BesarBayaran'] ?></td>
              <td>
                <span><a href="sppsiswa.php?tahunajaran=<?=urlencode($selectedTahunAjaran)."&kelas=".$row['KodeKelas'] ?>"> Lihat Siswa</a></span>
              </td>
            </tr>
          </span>
        </li>
          <?php $i++; endwhile; ?>
        </tbody>
      </table>
      <div id="respon"></div>
    </div>
    <?php } else {
    echo "<p>Data Kelas untuk tahun ajaran tersebut kosong.</p> ";
    }} } elseif ($parameter1 && $parameter2) {
      $stmtKelas = $conn->prepare("SELECT tbkelas.*, tbspp.TahunAjaran, tbspp.Tingkat, tbspp.BesarBayaran FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE KodeKelas = ?");
      $stmtKelas->bind_param("s",$kelas);
      $stmtKelas->execute();
      $resultKelas = $stmtKelas->get_result();
      $dataKelas = $resultKelas->fetch_assoc();
      if ($resultKelas->num_rows != 0) {
        var_dump($dataKelas);
  ?>
  <a href="sppsiswa.php?tahunajaran=<?=$selectedTahunAjaran?>" class="link">&#171; Kembali</a>
    <h3>Biodata Kelas</h3>
    <table>
      <tr>
        <td>Nama Kelas</td>
        <td>:</td>
        <td><?= $dataKelas['NamaKelas'] ?></td>
        <td style="width: 20px;"></td>
        <td>Jurusan</td>
        <td>:</td>
        <td><?= $dataKelas['Jurusan'] ?></td>
      </tr>
      <tr>
        <td>Tahun Ajaran</td>
        <td>:</td>
        <td><?= $dataKelas['TahunAjaran'] ?></td>
        <td style="width: 20px;"></td>
        <td>Tingkat</td>
        <td>:</td>
        <td><?= $dataKelas['Tingkat'] ?></td>
      </tr>
      <tr>
        <td>Jumlah Bayaran</td>
        <td>:</td>
        <td><?= "Rp.".$app->numberformat($dataKelas['BesarBayaran']) ?></td>
      </tr>
    </table>
    
    <h2>SPP Siswa Kelas <?=$kelas?></h2>
    <?php 
      $queryCekSppSiswa = "SELECT * FROM wip";
    ?>

    <?php }} ?>

    <!-- MODAL BOX -->
    <div class="modal" id="modalBox">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h4><span id="modal-title">Tambah Data</span> Siswa</h4>
        <form id="formModal" action="prosestambahsiswa.php" method="post">
          <input type="hidden" id="hiddenNis" name="hiddenNis" value="">
          <label for="NIS"><b>NIS</b></label>
          <input type="number" placeholder="Masukkan NIS" name="NIS" id="nis" required>

          <label for="nama"><b>Nama Lengkap</b></label>
          <input type="text" placeholder="Masukkan Nama Lengkap" id="nama" name="nama" required>

          <label for="alamat"><b>Alamat</b></label>
          <input type="text" placeholder="Masukkan Alamat" id="alamat" name="alamat">

          <label for="telp"><b>No Telp</b></label>
          <input type="text" placeholder="Masukkan Telp" id="telp" name="telp">

          <!-- <label for="kelas"><b>Kelas</b></label>
          <select id="kelas" name="kelas">
            <?php //while($dataKelas = $resultKelas->fetch_assoc()): ?>
            <option value="<?//=$dataKelas['KodeKelas']?>"><?//=$dataKelas['TahunAjaran'] ?> | <?//= $dataKelas['NamaKelas'] ?></option>
            <?php //endwhile; ?>
          </select> -->
          <button id="tombolAksi" type="submit">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</body>
<script src="script.js"></script>
</html>