<?php

require_once 'App.php';
if (!$session) {
    header("Location:login.php");
}
$param = "";
$var;
if (!empty($_POST) && isset($_POST['nis']) && isset($_POST['kelas']) && is_array($_POST['nis'])) {
    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];
    $jumlahData = count($nis);
    $kelas = intval($kelas);
    $queryInsert = "INSERT INTO `tbsppsiswa`(`nis`, `kodekelas`) VALUES (?, ?)";
    $jumlahBerhasil = 0;

    //Get Informasi SPP dari kode kelas
    $stmtKelas = $conn->prepare("SELECT tbkelas.*, tbspp.TahunAjaran FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE KodeKelas = ?");
    $stmtKelas->bind_param("s", $kelas);
    $stmtKelas->execute();
    $resultKelas = $stmtKelas->get_result();
    $stmtKelas->close();
    $dataKelas = $resultKelas->fetch_assoc();
    $semester = explode('/', $dataKelas['TahunAjaran']);
    $selectedSemester = null;

    //Set Values
    $values = "";
    for ($i = 0; $i < 6; $i++) {
        $values .= "(?, ?, ?), ";
    }
    $values .= rtrim($values, ", ");

    //Tambah Siswa
    foreach ($nis as $id) {
        /*
        * Mendaftarkan siswa ke dalam kelas tersebut
        */
        $stmtInsert = $conn->prepare($queryInsert);
        $stmtInsert->bind_param('si', $id, $kelas);
        $stmtInsert->execute();
        if ($conn->affected_rows > 0) {
            $jumlahBerhasil += 1;
        }
        $stmtInsert->close();

        /*
        * Dapatkan kode spp siswa setelah terdaftar
        */
        $queryGetKodeSppSiswa = "SELECT `kode_spp_siswa` FROM `tbsppsiswa` WHERE `nis` = ? AND `kodekelas` = ?";
        $stmtGetKodeSppSiswa = $conn->prepare($queryGetKodeSppSiswa);
        $stmtGetKodeSppSiswa->bind_param('si', $id, $kelas);
        $stmtGetKodeSppSiswa->execute();
        $getKodeSppSiswa = $stmtGetKodeSppSiswa->get_result()->fetch_assoc();
        $kodeSppSiswa = $getKodeSppSiswa['kode_spp_siswa'];
        $bulan = $app->getMonth();

        /*
        * Masukan data pembayaran melalui kode spp yang telah didapatkan
        */
        $queryCreateSpp = "INSERT INTO `tbpembayaran`(`kode_spp_siswa`, `BulanDibayar`, `TahunDibayar`) VALUES $values";
        $stmtCreateSpp = $conn->prepare($queryCreateSpp);
        $stmtCreateSpp->bind_param(
            'ssssssssssssssssssssssssssssssssssss',
            $kodeSppSiswa,
            $bulan[0],
            $semester[0],
            $kodeSppSiswa,
            $bulan[1],
            $semester[0],
            $kodeSppSiswa,
            $bulan[2],
            $semester[0],
            $kodeSppSiswa,
            $bulan[3],
            $semester[0],
            $kodeSppSiswa,
            $bulan[4],
            $semester[0],
            $kodeSppSiswa,
            $bulan[5],
            $semester[0],
            $kodeSppSiswa,
            $bulan[6],
            $semester[0],
            $kodeSppSiswa,
            $bulan[7],
            $semester[1],
            $kodeSppSiswa,
            $bulan[8],
            $semester[1],
            $kodeSppSiswa,
            $bulan[9],
            $semester[1],
            $kodeSppSiswa,
            $bulan[10],
            $semester[1],
            $kodeSppSiswa,
            $bulan[11],
            $semester[1]
        );
        /*
        * Jika ada error atau kesalahan
        */
        if ($stmtCreateSpp->error) {
            die(var_dump($stmtCreateSpp->error) . "<br><br>");
        }

        $stmtCreateSpp->execute();
        $stmtCreateSpp->close();
    }

    if ($conn->errno) {
        die($conn->error);
    }
    $gagal = $jumlahData - $jumlahBerhasil;
    $extraMessage = ($gagal > 0) ? $gagal . " Data Gagal Ditambahkan. " . $jumlahBerhasil : $jumlahBerhasil;
    $app->setpesan($extraMessage . " Data Berhasil", "ditambahkan");


    $param = "?tahunajaran=" . urlencode($dataKelas['TahunAjaran']) . "&kelas=$kelas";
}
header("Location: pendataansiswa.php$param");
