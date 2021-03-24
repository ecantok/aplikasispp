<?php
require_once 'app.php';
  if (!empty($_POST)) {
    $nis = $_POST['NIS'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $kelas = $_POST['kelas'];
    $stmt = $conn->prepare("UPDATE tbsiswa SET 
    NamaSiswa = ?,
    Alamat = ?,
    NoTelp = ?,
    KodeKelas = ?
    WHERE NIS= ?");
    $stmt ->bind_param('sssss',$nama,$alamat,$telp,$kelas,$nis);
    $stmt->execute();
    if ($conn->affected_rows > 0) {
      setpesan("Siswa Berhasil","diedit");
    } else {
      setpesan("Siswa Gagal", "diedit");
    }
  }
  header("Location: siswa.php")
?>