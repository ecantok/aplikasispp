<?php

require_once 'app.php';
if (!$session) {
  header("Location:login.php");
}
if (!empty($_GET['id'])) {
  $id = $_GET["id"];
  $stmt2 = $conn->prepare("DELETE FROM tblogin WHERE Username = ?");
  $stmt2->bind_param("s",$id);
  $stmt2->execute();
  $stmt2->close();

  $stmt3 = $conn->prepare("DELETE FROM tbpembayaran WHERE NIS = ?");
  $stmt3->bind_param("s",$id);
  $stmt3->execute();
  $stmt3->close();

  $stmt = $conn->prepare("DELETE FROM tbsiswa WHERE NIS = ?");
  $stmt->bind_param("s",$id);
  $stmt->execute();
  $stmt->close();


  if (!$conn->errno) {
    setpesan("Siswa Berhasil","dihapus");
  } else {
    setpesan("Siswa Gagal", "dihapus");
  }
  header("Location:siswa.php");
}
?>