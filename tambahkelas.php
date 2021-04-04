<?php

require_once 'app.php';
if (!empty($_POST)) {
    $NamaKelas = $_POST['NamaKelas'];
    $KodeSPP = $_POST['KodeSPP'];
    $Jurusan = $_POST['Jurusan'];

    $query = "INSERT INTO `tbkelas` VALUES ('', ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $KodeSPP, $NamaKelas, $Jurusan);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->affected_rows > 0) {
        $app->setpesan("Kelas Berhasil", "ditambahkan");
    } else {
        $app->setpesan("Kelas Gagal", "ditambahkan", "red");
    }
}
header("Location: kelas.php");