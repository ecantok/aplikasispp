<?php

require_once 'app.php';
  if (!empty($_POST)) {
    $id = $_POST['KodeSPP'];
    $TahunAjaran = $_POST['TahunAjaran'];
    $Tingkat = $_POST['Tingkat'];
    $BesarBayaran = $_POST['BesarBayaran'];
    
    $query = "UPDATE `tbspp` SET `TahunAjaran`= ?,`Tingkat`= ?,`BesarBayaran`= ? WHERE KodeSPP = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss",$TahunAjaran,$Tingkat,$BesarBayaran,$id);
    $stmt->execute();
    if ($conn->affected_rows > 0) {
      setpesan("Data Spp Berhasil","Diedit");
    } else {
      setpesan("Data Spp Berhasil", "Diedit","red");
    }
  }
  header("Location: spp.php");

?>