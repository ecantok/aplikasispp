<?php
require_once 'app.php';
if (!$session) {
  header("Location:index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History Transaksi || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'navbar.php'; ?>
<div class="container">
    <h2>History Transaksi Spp</h2>
    <div>
      <?php 
        $app->pesanDialog();
      ?>
        
    </div>
  <br>
  <div style="overflow-x: auto;">
      <table class="table-view">
      <thead>
        <th>No.</th>
        <th>ID Transaksi</th>
        <th>Nama Petugas</th>
        <th>Tanggal Transaksi</th>
        <th>Metode Transaksi</th>
        <th>Jumlah Bayaran</th>
        <th>Kembalian</th>
      </thead>
      <tbody>
        <?php
          $where = "";
          if ($app->cekPemissionLevel($levelUser, "Siswa")) {
            $q = "SELECT tbtransaksi.*, tbpetugas.NamaPetugas FROM `tbtransaksi` LEFT JOIN tbpetugas ON tbpetugas.KodePetugas = tbtransaksi.kodepetugas JOIN tbpembayaran ON tbtransaksi.kodepembayaran = tbpembayaran.KodePembayaran JOIN tbsppsiswa ON tbsppsiswa.kode_spp_siswa = tbpembayaran.kode_spp_siswa WHERE NIS = ? ORDER BY `tbtransaksi`.`tgl_transaksi` DESC";
            $stmtHistory=$conn->prepare($q);
            $stmtHistory->bind_param("i", $idUser);
          } else {
            $q = "SELECT tbtransaksi.*, tbpetugas.NamaPetugas FROM `tbtransaksi` LEFT JOIN tbpetugas ON tbpetugas.KodePetugas = tbtransaksi.kodepetugas JOIN tbpembayaran ON tbtransaksi.kodepembayaran = tbpembayaran.KodePembayaran JOIN tbsppsiswa ON tbsppsiswa.kode_spp_siswa = tbpembayaran.kode_spp_siswa ORDER BY `tbtransaksi`.`tgl_transaksi` DESC";
            $stmtHistory=$conn->prepare($q);
          }
          $stmtHistory->execute();
          $resultHitory = $stmtHistory->get_result();
          $fetchHisory = $resultHitory->fetch_all(MYSQLI_ASSOC);
          $no = 1;
          if ($resultHitory->num_rows >= 1) {
          foreach ($fetchHisory as $dataTransaksi ) {
        ?>
          <tr>
            <td><?=$no?></td>
            <td><?=$dataTransaksi['idtransaksi']?></td>
            <td><?=$dataTransaksi['NamaPetugas']?></td>
            <td><?=$dataTransaksi['tgl_transaksi']?></td>
            <td><?=$dataTransaksi['metode_transaksi']?></td>
            <td><?=$dataTransaksi['jumlah_bayaran']?></td>
            <td><?=$dataTransaksi['kembalian']?></td>
          </tr>
          <?php $no++; } } else { ?>
            <td colspan="8"> Data Kosong</td>
          <?php }
          $stmtHistory->close();
        ?>
      </tbody>
    </table>
  </div>
  <div id="respon"></div>
  <p class="text-info"><i>Histroy melihat data transaksi yang telah dilakukan</i></p>
</div>
  <?php require_once "footer.php";?>
</body>
<script>
  const selectTh = document.getElementById("tahunajaran");
  const address = "getdataspp.php";
  selectTh.addEventListener('change', (event)=> {
      kodespp = event.target.value;
      if (kodespp == "") {
        document.getElementById("tabel").innerHTML = "Data belum dipilih";
        return;
      } else {
        ajax(address, kodespp);
      }
    });
  <?php if (!empty($_GET['q'])) {
    echo "
    const kodesppGet = '{$_GET['q']}';
    ajax(address, kodesppGet);
    ";
  }?>
  function ajax(addr, param) {
    if (window.XMLHttpRequest) {
      //Browser untuk IE7+, Firefox, Chrome, and Opera, Safari
      xmlhttp = new XMLHttpRequest();
    } else {
      //Browser untuk IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function(){
      if (this.readyState == 4 & this.status == 200) {
        document.getElementById("respon").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET",""+addr+"?q="+param,true);
    xmlhttp.send();
  }
</script>
<script src="navbar.js"></script>
</html>