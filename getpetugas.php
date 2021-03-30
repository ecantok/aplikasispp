<?php
require_once "app.php";
if ($selectedUrl == "getpetugas.php") {
  //Don't come here
  // header("location:index.php");
}
if (!empty($_GET['kodepetugas'])) {
  $KodePetugas = $_GET['kodepetugas'];
  $stmt = $conn->prepare("SELECT tbpetugas.*, tblogin.Username FROM `tbpetugas`
  JOIN tblogin ON tblogin.kode_petugas = tbpetugas.KodePetugas WHERE KodePetugas = ?");
  $stmt -> bind_param('s',$KodePetugas);
  $stmt -> execute();
  $result = $stmt -> get_result();
  $data = $result ->fetch_assoc();
  echo json_encode($data);

} 
?>