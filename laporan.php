<?php

  require_once 'app.php';
  if (!$session && $app->cekPemissionLevel($levelUser)===false) {
    header("Location:login.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan | Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php require_once 'navbar.php'; ?>
    <div class="container">
      <h2>Generate Laporan</h2>
      <ul>
        <li class="li"><a class="link" href="cetaklaporan.php?cetak=spp" target="_blank">Laporan SPP</a></li>
        <li class="li"><a class="link" href="cetaklaporan.php?cetak=petugas" target="_blank">Laporan Petugas</a></li>
        <li class="li"><a class="link" href="cetaklaporan.php?cetak=kelas" target="_blank">Laporan Kelas</a></li>
        <li class="li"><a class="link" href="laporan.php?cetak=siswa">Laporan Pembayaran SPP</a></li>
      </ul>
      <?php 
        if ((!empty($_GET['cetak'])&&$_GET['cetak']=='siswa')||isset($_GET['nis'])) {
      ?>
      <hr>
      <form method="get">
        <div class="flex">
          <div>
            <label for="nis"><b>NIS</b></label>
            <?php $nis = (!empty($_GET['nis'])&& $_GET['nis'] != '')? $_GET['nis']: ''; ?>
            <input type="text" name="nis" id="nis" value="<?=$nis?>">
          </div>
          <input style="margin: 15px 10px;" type="submit" value="Cari">
        </div>
      </form>
    <?php  }
      if (isset($_GET["nis"])) {
        $query = "SELECT tbspp.TahunAjaran, tbsppsiswa.kode_spp_siswa FROM tbsiswa 
        JOIN tbsppsiswa ON tbsiswa.NIS = tbsppsiswa.nis
        JOIN tbkelas ON tbkelas.KodeKelas = tbsppsiswa.kodekelas
        JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP
        WHERE tbsppsiswa.nis = ?
        ";
        $stmtTahunAjaran=$conn->prepare($query);
        $stmtTahunAjaran->bind_param("s", $_GET['nis']);
        $stmtTahunAjaran->execute();
        $result = $stmtTahunAjaran->get_result();
        if ($result->num_rows > 0) {
        $result->fetch_all(MYSQLI_ASSOC);
        ?>
        <form action="cetaklaporan.php" method="get" target="_blank">
          <input type="hidden" name="cetak" value="pembayaran">
          <div class="flex">
            <div>
              <label for="kodespp"><b>Pilih Tahun Ajaran</b></label> 
              <select name='kodespp' id="kodespp" required>
              <?php
                foreach ($result as $data ) {
                  if (isset($_GET['kodespp'])&&!empty($_GET['kodespp']) && $_GET['kodespp']==$data['kode_spp_siswa']) {
                    echo "
                    <option value=".$data['kode_spp_siswa']." selected>".$data['TahunAjaran']."</option>
                    ";
                  } else {
                    echo "
                    <option value=".$data['kode_spp_siswa'].">".$data['TahunAjaran']."</option>
                    ";
                  }
                }
                echo "</select></td>";
                } else {
                  ?>
                    <p>Data SPP tidak ada</p>
                  <?php
                } 
              ?>
            </div>
            <input style="margin: 15px 10px;" type="submit" value="Cetak">
          </div>
        </form>
        <?php
      }
    ?>
    <?php /* if (!empty($_GET['cetak'])&&$_GET['cetak']=='siswa') {
      $result = $conn->query("SELECT tbs.NIS , tbk.*, tbspp.TahunAjaran FROM tbsiswa tbs LEFT JOIN tbkelas tbk ON tbs.Kodekelas = tbk.KodeKelas JOIN tbspp ON tbk.KodeSPP = tbspp.KodeSPP ORDER BY tbk.NamaKelas ASC");
      ?>
      <hr>
      <form style="margin-top: 22px;" action="cetaklaporan.php" method="get">
      <h3>Masukkan Data Siswa</h3>
      <div class="flex">
        <div>
          <label for="tahunajaran"><b>NIS</b></label>
          <input type="hidden" name="cetak" value="pembayaran">
          <select name="nis" id="nis">
            <?php while ($dataSiswa = $result->fetch_assoc()) {
              echo "<option value='{$dataSiswa['NIS']}'>{$dataSiswa['TahunAjaran']} | {$dataSiswa['NamaKelas']} |{$dataSiswa['NIS']}</option>";
            } ?>
          </select>
          
        </div>

        <input style="margin: 15px 10px;" type="submit" value="Cetak">
      </div>
    </form>
      <?php
    } */?>
  </div>
  <?php require_once "footer.php";?>
  <script src="navbar.js"></script>
</body>
</html>