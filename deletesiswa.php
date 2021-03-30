<?php

require_once 'app.php';
if (!$session) {
  header("Location:login.php");
}
if (!empty($_GET['nis'])) {
  $id = $_GET["nis"];
  $action = $_GET["action"];
  if ($action == "Hapus User") {
    $stmt2 = $conn->prepare("DELETE FROM tblogin WHERE Username = ?");
    $stmt2->bind_param("s",$id);
    $stmt2->execute();
    $stmt2->close();
  } elseif ($action == "Hapus Siswa") {
  
    $stmt = $conn->prepare("DELETE FROM tbsiswa WHERE NIS = ?");
    $stmt->bind_param("s",$id);
    $stmt->execute();
    $stmt->close(); 
  }
  if (!$conn->errno) {
    $app->setpesan("Siswa Berhasil","dihapus");
  } else {
    $app->setpesan("Siswa Gagal", "dihapus");
  }
  header("Location:siswa.php");
}
?>