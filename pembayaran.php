<?php
require_once 'app.php';
require_once 'navbar.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session||$app->cekPemissionLevel($levelUser,"Siswa")) {
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
  <title>Entri Pembayaran || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'navbar.php'; ?>
<div class="container">
    
    <h2>Entri Pembayaran Spp</h2>
    <div>
      <?php 
        $app->pesanDialog();
      ?>
        
    </div>
    <div style="display: none;">
      <button id="tampilModal">Tambah Data Kelas</button>
    </div>
    
    <form action="" method="get">
      <div class="flex">
        <div>
          <label for="tahunajaran"><b>NIS</b></label>
          <?php $nis = (!empty($_GET['nis'])&& $_GET['nis'] != '')? $_GET['nis']: ''; ?>
          <input type="text" name="nis" id="nis" value="<?=$nis?>">

        </div>

        <input style="margin: 15px 10px;" type="submit" value="Cari">
      </div>
    </form>
    <hr>
    <?php
      if (!empty($_GET['nis'])&& $_GET['nis'] != '') {
        $stmtSiswa = $conn->prepare("SELECT tbsiswa.*, tbkelas.*, tbspp.* FROM tbsiswa JOIN tbkelas ON tbsiswa.Kodekelas = tbkelas.KodeKelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE NIS = ?");
        $stmtSiswa->bind_param("s",$_GET['nis']);
        $stmtSiswa->execute();
        $resultSiswa = $stmtSiswa->get_result();
        $dataSiswa = $resultSiswa->fetch_assoc();
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
  <h3>Tagihan Spp</h3>
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
        <th>Action</th>
      </thead>
      <tbody>
        
        <?php $stmtSpp = $conn->prepare("SELECT tbpembayaran.*, tbpetugas.* FROM tbpembayaran JOIN tbpetugas ON tbpembayaran.KodePetugas = tbpetugas.KodePetugas WHERE NIS = ? ORDER BY `tbpembayaran`.`TahunDibayar` ASC");
        $stmtSpp->bind_param("s",$_GET['nis']);
        $stmtSpp->execute();
        $resultSPP = $stmtSpp->get_result();
        $no = 1;
        while ($dataSPP = $resultSPP->fetch_assoc()) {
          ?>
          <tr>
            <td><?=$no?></td>
            <td><?=$dataSPP['KodePembayaran']?></td>
            <td><?=$dataSPP['NamaPetugas']?></td>
            <td style="text-align: center;"><?= ($dataSPP['TglPembayaran']=='0000-00-00')? "-" : $dataSPP['TglPembayaran']?></td>
            <td><?=$dataSPP['BulanDibayar']?></td>
            <td><?=$dataSPP['TahunDibayar']?></td>
            <td style="text-align: center;"><?=$dataSPP['StatusPembayaran']?></td>
            <td style="text-align: center;"><?php
            if($dataSPP['StatusPembayaran'] =='-'){ echo
            "<a href='entripembayaran.php?act=bayar&id={$dataSPP['KodePembayaran']}'>Bayar</a>"; } else {
                echo"<a style='color:red' href='entripembayaran.php?act=batal&id={$dataSPP['KodePembayaran']}'>Batal</a>" ;
            
            } 
            ?> </td>
            
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
  }} ?>
  <p><i>Pembayaran SPP dilakukan dengan cara Mencari Tagihan Siswa dengan NIS melalui form diatas, kemudian dilakukan entri pembayaran</i></p>
</body>
<script src="script.js"></script>
</html>