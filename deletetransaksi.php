<?php

require_once 'app.php';
if (!$session && $app->cekPemissionLevel($levelUser)) {
    header("Location:index.php");
}
if (!empty($_GET['id'] && !empty($_GET['kodepembayaran']))) {
    $id = $_GET["id"];
    $stmt = $conn->prepare("DELETE FROM tbtransaksi WHERE idtransaksi = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    if ($conn->affected_rows > 0) {
        $app->setpesan("Data Transaksi Berhasil", "dihapus");
    } else {
        $app->setpesan("Data Transaksi Gagal", "dihapus");
    }
    header("Location:datasppsiswa.php?kodepembayaran={$_GET['kodepembayaran']}");
}
