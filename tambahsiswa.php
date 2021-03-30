<?php
require_once 'app.php';
  if (!empty($_POST)) {
    $nis = $_POST['NIS'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $nins= intval($nis);

    $stmtCek = $conn->prepare("SELECT NIS FROM tbsiswa WHERE NIS = ?");
    $stmtCek->bind_param('i',$nis);
    $stmtCek->execute();
    $resultCek = $stmtCek->get_result();
    if ($resultCek->num_rows == 0){
      $query = "INSERT INTO `tbsiswa` (`NIS`, `NamaSiswa`, `Alamat`, `NoTelp`) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("isss",$nis,$nama,$alamat,$telp);
      $stmt->execute();
      
      if ($conn->errno) {
        die($conn->error);
      }
      if ($conn->affected_rows > 0) {
        $stmt->close();
        $app->setpesan("Siswa Berhasil","ditambahkan");
      } else {
        $stmt->close();
        $app->setpesan("Siswa Gagal", "ditambahkan");
      }
    } else {
      $app->setpesan("NIS tidak boleh sama!");
    }
  }
  header("Location: siswa.php")
?>