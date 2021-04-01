<?php
require_once "App.php";
if (!empty($_GET['kodespp'])) {
  $kodespp = $_GET['kodespp'];
  $stmt = $conn->prepare("SELECT * FROM tbspp WHERE KodeSpp = ?");
  $stmt -> bind_param('s',$kodespp);
  $stmt -> execute();
  $result = $stmt -> get_result();
  $data = $result ->fetch_assoc();
  echo json_encode($data);
} 
?>