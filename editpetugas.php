<?php
require_once 'app.php';
  if (!empty($_POST)) {
    $id = $_POST['id'];
    $NamaPetugas = $_POST['NamaPetugas'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Password2 = $_POST['Password2'];
    $Alamat = $_POST['Alamat'];
    $Telp = $_POST['Telp'];
    $Jabatan = $_POST['Jabatan'];
    $cekPass = konfirmasiPassword($Password,$Password2);
    if ($cekPass) {
      if ($cekPass != '') {
        $query = "UPDATE `tbpetugas` SET
      `Username`= ?,
      `Password`= md5(?),
      `NamaPetugas`= ?,
      `Alamat`= ?,
      `Telp`= ?,
      `Jabatan`= ? WHERE KodePetugas = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssssss",$Username,$Password,$NamaPetugas,$Alamat,$Telp,$Jabatan,$id);
      
      $query2 = "UPDATE `tblogin` SET
      `Username`= ?,
      `Password`= md5(?) WHERE Username = ?";
      $stmt2 = $conn->prepare($query2);
      $stmt2->bind_param("sss",$Username,$Password,$id);
      } else {
        $query = "UPDATE `tbpetugas` SET
      `Username`= ?,
      `NamaPetugas`= ?,
      `Alamat`= ?,
      `Telp`= ?,
      `Jabatan`= ? WHERE KodePetugas = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssssss",$Username,$NamaPetugas,$Alamat,$Telp,$Jabatan,$id);
      
      $query2 = "UPDATE `tblogin` SET
      `Username`= ? WHERE Username = ?";
      $stmt2 = $conn->prepare($query2);
      $stmt2->bind_param("ss",$Username,$id);
      }
      $stmt->execute();
      $stmt2->execute();
      
      if ($conn->errno == 0) {
        setpesan("Petugas Berhasil","diedit");
      } else {
        setpesan("Petugas Gagal", "diedit");
      }
    } else {
      setpesan("Password Konfirmasi Salah!");
    }
  }
  header("Location: petugas.php")
?>

