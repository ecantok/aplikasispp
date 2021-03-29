<?php

require_once 'app.php';
if (!$session&& $app->cekPemissionLevel($levelUser, 'Siswa')) {
  header("Location:index.php");
}
if (!empty($_GET)&&$_GET['id']!=''&&$_GET['act']!='') {
  @$nis = $_GET['nis'];
  
  $KodePembayaran = $_GET['id'];
  $queryCek = "SELECT tbsppsiswa.kode_spp_siswa, tbsppsiswa.nis FROM tbsppsiswa 
  JOIN tbpembayaran ON tbpembayaran.kode_spp_siswa = tbsppsiswa.kode_spp_siswa 
  WHERE tbpembayaran.KodePembayaran = ?";
  $stmtCek = $conn->prepare($queryCek);
  $stmtCek->bind_param("s",$KodePembayaran);
  $stmtCek->execute();
  $resultCek= $stmtCek->get_result();
  if ($resultCek->num_rows == 1) {
    //Ambil data siswa
    $dataSiswa = $resultCek->fetch_assoc();
    $nis = $dataSiswa["nis"];
    $kodeSpp = $dataSiswa['kode_spp_siswa'];
    $stmtCek->close();

    //Mulai transaksi pembayaran
    $dataCek = $resultCek->fetch_assoc();
    $today =date("Y-m-d");
    if ($_GET['act']=='bayar') {
      $stmt = $conn->prepare("UPDATE `tbpembayaran` SET 
      `KodePetugas`= ?,
      `TglPembayaran`= ?,
      `StatusPembayaran`='LUNAS'  
      WHERE KodePembayaran = ?
    ");
    } elseif ($_GET['act']=='batal') {
      $today = "0000-00-00";
      $stmt = $conn->prepare("UPDATE `tbpembayaran` SET 
      `KodePetugas`= ?,
      `TglPembayaran`= ?,
      `StatusPembayaran`='-'  
      WHERE KodePembayaran = ?
      ");
    } else {
      $app->setpesan("Aksi Entri Pembayaran NIS {$nis} Gagal", "dibuat ...","red");
      exit;
    }
    $stmt->bind_param("sss",$idUser,$today, $KodePembayaran);
    $stmt->execute();
    if ($conn->affected_rows > 0) {
      $app->setpesan("Entri Pembayaran  dengan NIS {$nis} Berhasil","dibuat");
    } else {
      $app->setpesan("Entri Pembayaran  dengan NIS {$nis} Gagal", "dibuat","red");
    }
  }
} 
      header("Location: pembayaran.php?nis={$nis}&q={$kodeSpp}");
      
?>