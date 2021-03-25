<?php

require_once 'app.php';
  if (!empty($_POST)) {
    $TahunAjaran = $_POST['TahunAjaran'];
    $Tingkat = $_POST['Tingkat'];
    $BesarBayaran = $_POST['BesarBayaran'];
    
    $query = "INSERT INTO `tbspp`(`KodeSPP`, `TahunAjaran`, `Tingkat`, `BesarBayaran`) VALUES ('', ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss",$TahunAjaran,$Tingkat,$BesarBayaran);
    $stmt->execute();
    if ($conn->affected_rows > 0) {
      $app->setpesan("Data Spp Berhasil","ditambahkan");
    } else {
      $app->setpesan("Data Spp Berhasil", "ditambahkan","red");
    }
  }
  header("Location: spp.php");
?>

?>