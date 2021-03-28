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

    <?php
      if (($parameter1&&!$parameter2)){
      if ($parameter1) {
        $stmtSpp = $conn->prepare("SELECT tbkelas.*, tbspp.* FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE tbspp.TahunAjaran = ?");
        $stmtSpp->bind_param("s",$selectedTahunAjaran);
        $stmtSpp->execute();
        $resultSpp = $stmtSpp->get_result();
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
    ?>
    <a href="sppsiswa.php?tahunajaran=<?=$selectedTahunAjaran?>" class="link">&#171; Kembali</a>
    <h2>Biodata Kelas</h2>
    <div style="overflow-x: auto;">
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
    </div>

    <hr>
    <!-- Data Siswa di kelas... -->
    <h2>Data Siswa di Kelas <?=$dataKelas['NamaKelas'] ?></h2>
    <div style="display: block;" class="mb">
      <button id="tampilModal" class="button">Tambah Siswa</button>
    </div>
    <div id="tableId" style="overflow-x:auto;">
      <table class="table-view">
        <thead>
          <th>No.</th>
          <th>NIS</th>
          <th>Nama</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php
            $querySppSiswa = "SELECT tbsppsiswa.kode_spp_siswa, tbsppsiswa.NIS, tbsiswa.NamaSiswa FROM tbsppsiswa JOIN tbsiswa ON tbsppsiswa.NIS = tbsiswa.NIS JOIN tbkelas ON tbsppsiswa.kodekelas = tbkelas.KodeKelas WHERE tbsppsiswa.kodekelas = ?";
            $stmtSppSiswa= $conn->prepare($querySppSiswa);
            $stmtSppSiswa->bind_param("s", $kelas);
            $stmtSppSiswa->execute();
            
            $resultSppSiswa = $stmtSppSiswa->get_result();
            if ($resultSppSiswa->num_rows == 0) {
              echo "<td colspan = '4' style='text-align: center;'><b>Data Kosong</b></td>";
            }
            $cekSiswa = []; $i=1; while ($dataSppSiswa = $resultSppSiswa ->fetch_assoc() ):
          ?>
          <tr>
            <td><?=$i ?></td>
            <td><?=$dataSppSiswa['NIS'] ?></td>
            <td><?=$dataSppSiswa['NamaSiswa'] ?></td>
            <td>
              <span><a href="deletesppsiswa.php?id=<?= $dataSppSiswa['kode_spp_siswa'] ?>"> Hapus</a></span>
            </td>
          </tr>
        </li>
          <?php array_push($cekSiswa, $dataSppSiswa['NIS']); $i++; endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php }} ?>

    <!-- MODAL BOX -->
    <div class="modal" id="modalBox">
      <div class="modal-content-small">
        <span class="close">&times;</span>
        <h4><span id="modal-title">Tambah Siswa</span></h4>
        <?php $resultSiswa=$conn->query("SELECT tbsiswa.NIS, tbsiswa.Namasiswa FROM tbsiswa WHERE nis != 1");
        if ($resultSiswa->num_rows > 0) {
          ?>
        <form id="formModal" action="asdfghjkl" method="post">
          <input type="hidden" name="kelas" value="<?=$kelas ?>">
          <table style="border: 1px solid #ccc;">
            <thead>
              <th></th>
              <th>NIS</th>
              <th>Nama SIswa</th>
            </thead>
            <tbody>
              <?php
                while($row=mysqli_fetch_array($resultSiswa)){
                    if (in_array($row['NIS'],$cekSiswa)) {
                      continue;
                    }
                    ?>
                    <tr>
                      <td><input type="checkbox" value="<?php echo $row['NIS']; ?>" name="nis[]"></td>
                      <td><?= $row['NIS']; ?></td>
                      <td><?= $row['Namasiswa']; ?></td>
                    </tr>
                    <?php
                  }
              ?>
              
            </tbody>
          </table>
          <br>
          <input class="button" id="tombolAksi" type="submit" value="Tambah Siswa">
        </form>
        <?php
        } else {
          echo "Data Siswa kosong";
        }
        ?>
      </div>
    </div>
  </div>
  <?php require_once "footer.php" ?>
</body>
<script src="script.js"></script>
</html>