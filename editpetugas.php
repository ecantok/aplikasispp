<?php
require_once 'App.php';
  if (!empty($_POST)) {
    $id = $_POST['id'];
    $kodepetugas = $_POST['kodepetugas'];
    $NamaPetugas = $_POST['NamaPetugas'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Password2 = $_POST['Password2'];
    $Alamat = $_POST['Alamat'];
    $Telp = $_POST['Telp'];
    $Jabatan = $_POST['Jabatan'];
    $cekPass = $app->konfirmasiPassword($Password,$Password2);
    $stmtCek = $conn->prepare("SELECT KodePetugas from tbpetugas WHERE KodePetugas = ?");
    $stmtCek ->bind_param("s", $kodepetugas);
    $stmtCek->execute();
    $resultCek = $stmtCek->get_result();
    if ($cekPass && $resultCek->num_rows == 1) {
		if ($cekPass != '') {
			$query2 = "UPDATE `tblogin` SET
			`Username`= ?,
			`Password`= md5(?) WHERE kode_petugas = ?";
			$stmt2 = $conn->prepare($query2);
			$stmt2->bind_param("sss",$Username,$Password,$id);
			$query = "UPDATE `tbpetugas` SET
			KodePetugas = ?,
			`NamaPetugas`= ?,
			`Alamat`= ?,
			`Telp`= ?,
			`Jabatan`= ? WHERE KodePetugas = ?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ssssss",$kodepetugas,$NamaPetugas,$Alamat,$Telp,$Jabatan,$id);
			
		} else {
			$query2 = "UPDATE `tblogin` SET
			`Username`= ? WHERE kode_petugas = ?";
			$stmt2 = $conn->prepare($query2);
			$stmt2->bind_param("ss",$Username,$id);
			$query = "UPDATE `tbpetugas` SET
			`NamaPetugas`= ?,
			`Alamat`= ?,
			`Telp`= ?,
			`Jabatan`= ? WHERE KodePetugas = ?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("sssss",$NamaPetugas,$Alamat,$Telp,$Jabatan,$id);
			
		}
		$stmt->execute();
		$stmt2->execute();
		
		if ($conn->errno == 0) {
		$app->setpesan("Petugas Berhasil","diedit");
		} else {
		$app->setpesan("Petugas Gagal", "diedit");
		}
    } else {
      $app->setpesan("Password Konfirmasi Salah!");
    }
  }
  header("Location: petugas.php")
?>

