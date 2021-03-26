<?php

require_once 'app.php';
if (!$session) {
  header("Location:login.php");
}
if (!empty($_GET['nis'])) {
  $id = $_GET["nis"];
  $action = $_GET["action"];

  $stmt2 = $conn->prepare("DELETE FROM tblogin WHERE Username = ?");
  $stmt2->bind_param("s",$id);
  $stmt2->execute();
  $stmt2->close();

  if ($action == "Hapus User") {
    $stmtUser = $conn->prepare("UPDATE tbsiswa SET `Username` = '' WHERE NIS = ?");
    $stmtUser->bind_param("s",$id);
    $stmtUser->execute();
    $stmtUser->close();
  } elseif ($action == "Hapus Siswa") {
    $stmt3 = $conn->prepare("DELETE FROM tbpembayaran WHERE NIS = ?");
    $stmt3->bind_param("s",$id);
    $stmt3->execute();
    $stmt3->close();
  
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