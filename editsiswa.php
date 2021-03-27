<?php
require_once 'app.php';
  if (!empty($_POST)) {
    $hiddenNis = intval($_POST['hiddenNis']);
    $nis = intval($_POST['NIS']);
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $number = 1;

    $stmtreset = $conn->prepare("UPDATE tbpembayaran SET 
    NIS = ? WHERE NIS= ?");
    $stmtreset->bind_param('ii',$number,$hiddenNis);
    // $kelas = $_POST['kelas'];
    $stmt = $conn->prepare("UPDATE tbsiswa SET 
    NIS = ?,
    NamaSiswa = ?,
    Alamat = ?,
    NoTelp = ?
    WHERE NIS= ?");
    $stmt ->bind_param('isssi',$nis,$nama,$alamat,$telp,$hiddenNis);
    
    $stmtPembayaran = $conn->prepare("UPDATE tbpembayaran SET 
    NIS = ? WHERE NIS= ?");
    $stmtPembayaran->bind_param('ii',$nis,$hiddenNis);
    if (($conn->errno && $stmt->errno && $stmtreset->errno) == 0) {
      $stmtreset->execute();
      $stmt->execute();
      $stmtPembayaran->execute();
      $app->setpesan("Siswa Berhasil","diedit");
    } else {
      $app->setpesan("Siswa Gagal", "diedit");
    }
  }
  header("Location: siswa.php")
?>