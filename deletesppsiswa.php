<?php

require_once 'app.php';
if (!$session && $app->cekPemissionLevel($levelUser)) {
  header("Location:index.php");
}
if (!empty($_GET['id'])) {
  $id = $_GET["id"];
  //Get Informasi SPP dari kode kelas
  $stmtKelas = $conn->prepare("SELECT tbsppsiswa.kodekelas, tbspp.TahunAjaran FROM tbsppsiswa 
  JOIN tbkelas ON tbkelas.KodeKelas = tbsppsiswa.kodekelas
  JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP
  WHERE tbsppsiswa.kode_spp_siswa = ?");
  $stmtKelas->bind_param("s",$id);
  $stmtKelas->execute();
  $resultKelas =$stmtKelas->get_result();
  $dataKelas = $resultKelas->fetch_assoc();
  $tahunAjaran = urlencode($dataKelas['TahunAjaran']);
  $kelas = $dataKelas['kodekelas'];

  //Delete
  $stmt = $conn->prepare("DELETE FROM tbsppsiswa WHERE kode_spp_siswa = ?");
  $stmt->bind_param("s",$id);
  $stmt->execute();

    if ($conn->affected_rows > 0) {
      $app->setpesan("Data SPP Berhasil","dihapus");
    } else {
      $app->setpesan("Data SPP Gagal", "dihapus");
    }
    $param = "setsppsiswa.php?tahunajaran=$tahunAjaran&kelas=$kelas";
  header("Location:$param");
}
?>