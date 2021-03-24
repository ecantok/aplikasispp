<?php

require_once 'app.php';
if (!$session) {
  header("Location:login.php");
}
if (!empty($_GET)&&$_GET['id']!=''&&$_GET['act']!='') {
  $KodePembayaran = $_GET['id'];
  $queryCek = "SELECT * FROM tbpembayaran WHERE KodePembayaran = ?";
  $stmtCek = $conn->prepare($queryCek);
  $stmtCek->bind_param("s",$KodePembayaran);
  $stmtCek->execute();
  $resultCek= $stmtCek->get_result();
  if ($resultCek->num_rows == 1) {
    $stmtCek->close();
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
      $stmt = $conn->prepare("UPDATE `tbpembayaran` SET 
      `KodePetugas`= ?,
      `TglPembayaran`= ?,
      `StatusPembayaran`='-'  
      WHERE KodePembayaran = ?
      ");
    } else {
      setpesan("Entri Pembayaran {$dataCek['NIS']} Gagal", "dibuat ...","red");
      exit;
    }
    $stmt->bind_param("sss",$idUser,$today, $KodePembayaran);
    $stmt->execute();
    if ($conn->affected_rows > 0) {
      setpesan("Entri Pembayaran {$dataCek['NIS']} Berhasil","dibuat");
    } else {
      setpesan("Entri Pembayaran {$dataCek['NIS']} Gagal", "dibuat","red");
    }
  }
} 
      header("Location: pembayaran.php?nis={$dataCek['NIS']}");
      
?>