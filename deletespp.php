<?php

require_once 'app.php';
if (!$session && $app->cekPemissionLevel($levelUser)) {
  header("Location:index.php");
}
if (!empty($_GET['id'])) {
  $id = $_GET["id"];
  $stmt = $conn->prepare("DELETE FROM tbspp WHERE KodeSPP = ?");
  $stmt->bind_param("s",$id);
  $stmt->execute();

    if ($conn->affected_rows > 0) {
      $app->setpesan("Data SPP Berhasil","dihapus");
    } else {
      $app->setpesan("Data SPP Gagal", "dihapus");
    }
  header("Location:spp.php");
}
?>