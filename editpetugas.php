<?php
require_once 'App.php';
if (!empty($_POST)) {
    //Ambil masukan input
    $id = $_POST['id'];
    $kodepetugas = $_POST['kodepetugas'];
    $NamaPetugas = $_POST['NamaPetugas'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Password2 = $_POST['Password2'];
    $Alamat = $_POST['Alamat'];
    $Telp = $_POST['Telp'];
    $Jabatan = $_POST['Jabatan'];


    $cekPass = $app->confirmPassword($Password, $Password2);
    $stmtCheck = $conn->prepare("SELECT KodePetugas from tbpetugas WHERE KodePetugas = :kodepetugas");
    $stmtCheck->bindParam(":kodepetugas", $kodepetugas);
    $stmtCheck->execute();
    $fetchCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    if ($cekPass && $resultCek->num_rows == 1) {
        if ($cekPass != '') {
            $query2 = "UPDATE `tblogin` SET
			`Username`= ?,
			`Password`= md5(?) WHERE kode_petugas = ?";
            $params2 = array(
                ":kp" => $kodepetugas,
                ":np" => $NamaPetugas,
                ":al" => $Alamat,
                ":tel" => $Telp,
                ":jab" => $Jabatan,
                ":id" => $id,
            );
            // $stmt2->bindParam("sss", $Username, $Password, $id);
            $query = "UPDATE `tbpetugas` SET
			KodePetugas = :kp,
			`NamaPetugas`= :np,
			`Alamat`= :al,
			`Telp`= :tel,
			`Jabatan`= :jab WHERE KodePetugas = :id";
            // $stmt->bind_param("ssssss", $kodepetugas, $NamaPetugas, $Alamat, $Telp, $Jabatan, $id);
        } else {
            $query2 = "UPDATE `tblogin` SET
			`Username`= ? WHERE kode_petugas = ?";
            // $stmt2->bind_param("ss", $Username, $id);
            $query = "UPDATE `tbpetugas` SET
			`NamaPetugas`= ?,
			`Alamat`= ?,
			`Telp`= ?,
			`Jabatan`= ? WHERE KodePetugas = ?";
            // $stmt->bind_param("sssss", $NamaPetugas, $Alamat, $Telp, $Jabatan, $id);
        }
        $stmt = $conn->prepare($stmt);
        $stmt->execute();
        $stmt2->execute();

        if ($conn->errno == 0) {
            $app->setpesan("Petugas Berhasil", "diedit");
        } else {
            $app->setpesan("Petugas Gagal", "diedit");
        }
    } else {
        $app->setpesan("Password Konfirmasi Salah!");
    }
}
header("Location: petugas.php");
