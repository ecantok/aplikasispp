<?php

require_once 'app.php';
if (!empty($_POST)) {
    $NamaKelas = $_POST['NamaKelas'];
    $Jurusan = $_POST['Jurusan'];
    $KodeKelas = $_POST['KodeKelas'];
    $KodeSPP = $_POST['KodeSPP'];

    $query = "UPDATE `tbkelas` SET 
    NamaKelas = ?,
    KodeSPP = ?,
    Jurusan = ?
    WHERE KodeKelas = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $NamaKelas, $KodeSPP, $Jurusan, $KodeKelas);
    $stmt->execute();
    $stmt->store_result();
    if ($conn->error == '') {
        $app->setpesan("Kelas Berhasil", "diedit");
    } else {
        $app->setpesan("Kelas Gagal", "diedit", "red");
    }
}
header("Location: kelas.php");
?>

?>