<?php
require_once 'app.php';
  switch ($_GET['cetak']) {
    case 'spp':
      $title = "Spp";
      $result = $conn ->query("SELECT * FROM tbspp ORDER BY TahunAjaran ASC");
      $thead = "<thead>
      <th>No.</th>
      <th>Kode SPP</th>
      <th>Tahun Ajaran</th>
      <th>Tingkat</th>
      <th>Besar Bayaran</th>
    </thead>";
      break;
    case 'kelas':
      $title = "Kelas";
      $result = $conn ->query("SELECT tbkelas.*, tbspp.TahunAjaran, tbspp.Tingkat FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP ORDER BY TahunAjaran ASC");
      $thead = "<thead>
      <th style='width: 20px;'>No.</th>
      <th>Tahun Ajaran</th>
      <th>Tingkat</th>
      <th>Jurusan</th>
      <th>Nama Kelas</th>
    </thead>";
      break;
    case 'petugas':
      $title = "Petugas";
      $result = $conn ->query("SELECT * FROM tbpetugas");
      $thead = "<thead>
      <th style='width: 20px;'>No.</th>
      <th>Nama Petugas</th>
      <th>Username</th>
      <th style='width:250px;'>Alamat</th>
      <th>Telp</th>
      <th>Jabatan</th>
    </thead>";
      break;
      case 'pembayaran':
        $title = "Pembayaran";
        $result = $conn ->query("SELECT * FROM tbspp");
        $thead = "<thead>
        <th style='width: 20px;'>No.</th>
        <th>Kode Pembayaran</th>
        <th>Petugas</th>
        <th>Tgl. Pembayaran</th>
        <th>Bulan Dibayar</th>
        <th>Tahun Dibayar</th>
        <th>Status</th>
      </thead>";
        break;
    default:
      header("Location:laporan.php");
      break;
  }

?>
<html lang="en">
<head>
<style>
  *{
    font-family: sans-serif;
  }
  .table-detail{
    border: solid 1px grey;
    border-collapse: collapse;
    border-spacing: 0;
    margin: 10px auto 10px auto;
  }
  .table-detail thead th{
    border: solid 1px grey;
    padding: 10px;
    text-decoration: none;
    text-align: center;
  }
  .table-detail tbody td{
    border: solid 1px grey;
    color: #333;
    padding: 10px;
  }
  </style>
</head>
<body>

<center>
  <h2>Aplikasi Pembayaran SPP</h2>
  <h6>
    Jalan GatotSubroto, No. 25 <br> 
    Denpasar - Bali<br>
    Telp : 081234567890<br>
  </h6>
  <h3>Data <?=$title?></h3>
</center>
<?php
    if (!empty($_GET['nis'])&& $_GET['nis'] != '') {
      $stmtSiswa = $conn->prepare("SELECT tbsiswa.*, tbkelas.*, tbspp.* FROM tbsiswa JOIN tbkelas ON tbsiswa.Kodekelas = tbkelas.KodeKelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE NIS = ?");
      $stmtSiswa->bind_param("s",$_GET['nis']);
      $stmtSiswa->execute();
      $resultSiswa = $stmtSiswa->get_result();
      $dataSiswa = $resultSiswa->fetch_assoc();
      if ($resultSiswa->num_rows != 0) {
  ?>
  <table>
    <tr>
      <td style="width: 150px;">NIS</td>
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
      <td><?= "Rp.".numberformat($dataSiswa['BesarBayaran']) ?></td>
    </tr>
  </table>
<?php }
} 
?>
<table class="table-detail" style="width: 100%; margin-top: 20px; border: 1;">
  <?= $thead ?>
  <tbody>
    
    <?php $no = 1; if ($_GET['cetak']=='pembayaran'&&$resultSiswa->num_rows != 0) {
        $stmtSpp = $conn->prepare("SELECT tbpembayaran.*, tbpetugas.* FROM tbpembayaran JOIN tbpetugas ON tbpembayaran.KodePetugas = tbpetugas.KodePetugas WHERE NIS = ? ORDER BY `tbpembayaran`.`TahunDibayar` ASC");
        $stmtSpp->bind_param("s",$_GET['nis']);
        $stmtSpp->execute();
        $resultSPP = $stmtSpp->get_result();
        
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
      } elseif ($_GET['cetak']=='spp'&&$result->num_rows != 0) {
        while ($data = $result->fetch_assoc()) {
          ?>
          <tr>
            <td><?=$no?></td>
            <td><?=$data['KodeSPP']?></td>
            <td><?=$data['TahunAjaran']?></td>
            <td><?=$data['Tingkat'] ?></td>
            <td><?=$data['BesarBayaran'] ?></td>
          </tr>
          <?php
          $no++;
        }
      } elseif ($_GET['cetak']=='kelas'&&$result->num_rows != 0) {
        while ($data = $result->fetch_assoc()) {
          ?>
          <tr>
            <td><?=$no ?></td>
            <td align="center"><?=$data['TahunAjaran'] ?></td>
            <td align="center"><?=$data['Tingkat']?></td>
            <td align="center"><?=$data['Jurusan'] ?></td>
            <td align="center"><?=$data['NamaKelas'] ?></td>
          </tr>
          <?php
          $no++;
        }
      } elseif ($_GET['cetak']=='petugas'&&$result->num_rows != 0) {
        while ($data = $result->fetch_assoc()) {
          ?>
          <tr>
            <td><?=$no ?></td>
            <td><?=$data['NamaPetugas'] ?></td>
            <td><?=$data['Username'] ?></td>
            <td><?=$data['Alamat'] ?></td>
            <td><?=$data['Telp'] ?></td>
            <td><?=$data['Jabatan'] ?></td>
          </tr>
          <?php
          $no++;
        }
      } 
      
      
    ?>
    
  </tbody>
</table>


<script>
//Untuk membuat halaman print
window.print();
</script>
  
</body>
</html>