<?php

require_once 'app.php';
if (!$session) {
    header("Location:login.php");
}
if (!empty($_GET['id'])) {
    $id = $_GET["id"];
    if ($id == 1) {
        $app->setpesan("Admin tidak boleh", "dihapus");
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM tbpetugas WHERE KodePetugas = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    if ($conn->affected_rows > 0) {
        $stmt2 = $conn->prepare("DELETE FROM tblogin WHERE Username = ?");
        $stmt2->bind_param("s", $id);
        $stmt2->execute();

        $app->setpesan("Petugas Berhasil", "dihapus");
    } else {
        $app->setpesan("Petugas Gagal", "dihapus");
    }
    header("Location:petugas.php");
}
