<?php
require_once 'app.php';
  if (!empty($_POST)) {
    $hiddenNis = intval($_POST['hiddenNis']);
    $nis = intval($_POST['NIS']);
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $number = 1;

    // $kelas = $_POST['kelas'];
    $stmt = $conn->prepare("UPDATE tbsiswa SET 
    NIS = ?,
    NamaSiswa = ?,
    Alamat = ?,
    NoTelp = ?
    WHERE NIS= ?");
    $stmt ->bind_param('isssi',$nis,$nama,$alamat,$telp,$hiddenNis);
    
    if (($conn->errno && $stmt->errno) == 0) {
      $stmt->execute();
      if ($conn->affected_rows > 0) {
        $app->setpesan("Siswa Berhasil","diedit");
      } else {
        $app->setpesan("Siswa Gagal", "diedit");
      }
    } else {
      $app->setpesan("Terjadi kesalahan error", "");
    }
  }
  header("Location: siswa.php")
?>