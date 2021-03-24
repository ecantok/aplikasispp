<?php

require_once 'app.php';
if (!$session) {
  header("Location:login.php");
}
if (!empty($_GET['id'])) {
  $stmt = $conn->prepare("DELETE FROM tbkelas WHERE KodeKelas = ?");
  $stmt->bind_param("s",$_GET["id"]);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->store_result();
    if ($stmt->affected_rows > 0) {
      setpesan("Kelas Berhasil","dihapus");
    } else {
      setpesan("Kelas Gagal", "dihapus");
    }
  header("Location:kelas.php");
}
?>