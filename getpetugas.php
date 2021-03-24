<?php
require_once "app.php";
if (!empty($_GET['kodepetugas'])) {
  $KodePetugas = $_GET['kodepetugas'];
  $stmt = $conn->prepare("SELECT KodePetugas,NamaPetugas,Username,Alamat,Telp,Jabatan FROM tbpetugas WHERE KodePetugas = ?");
  $stmt -> bind_param('s',$KodePetugas);
  $stmt -> execute();
  $result = $stmt -> get_result();
  $data = $result ->fetch_assoc();
  echo json_encode($data);

} 
?>