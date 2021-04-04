<?php

if (!empty($_POST)) {
    require_once 'app.php';
    $nis = $_POST["nis"];
    $telp = $_POST["telp"];
    $alamat = $_POST["alamat"];
    $namalengkap = $_POST['namalengkap'];

    //Password diambil dari fungsi acak generate random string
    $password = $app->generateRandomString();


    $stmt = $conn->prepare("SELECT NIS from tbsiswa WHERE NIS = ? AND NamaSiswa = ?");
    $stmt->bind_param('ss', $nis, $namalengkap);
    $stmt->execute();
    $cek = $stmt->get_result();
    //Cek apakah nis sudah ada di table siswa
    if ($cek->num_rows == 1) {
        $stmt->close();

        //Cek kesamaan apakah ada kesamaan
        //Kalau tidak bisa insert into tblogin
        $stmt2 = $conn->prepare("SELECT Username from tblogin WHERE Username = ?");
        $stmt2->bind_param('s', $nis);
        $stmt2->execute();
        $cek = $stmt2->get_result();

        //Cek kesamaan login
        if ($cek->num_rows == 0) {
            $stmt2->close();

            //Update data
            $stmt3 = $conn->prepare("UPDATE tbsiswa SET 
        NamaSiswa = ?,
        Alamat = ?,
        NoTelp = ?
        WHERE NIS= ?");
            $stmt3->bind_param('ssss', $namalengkap, $alamat, $telp, $nis);
            $stmt3->execute();
            $stmt3->get_result();
            if ($conn->affected_rows > 0) {
                $stmt3->close();

                //Insert data
                $level = "Siswa";
                $stmt4 = $conn->prepare("INSERT INTO tblogin(`Username`, `Password`, `Level`, `nis_siswa`, `kode_petugas`) VALUES( ?, MD5(?), ?, ?,NULL)");
                $stmt4->bind_param("ssss", $nis, $password, $level, $nis);
                $stmt4->execute();
                $stmt4->get_result();
                if ($conn->affected_rows > 0) {

                    //Kirimkan Username dan Password
                    $app->setpesan("<div style='text-align :center;'>Berhasil Terdaftar! Masukkan data berikut untuk login! <div>Username: <b>" . $nis . "</b> Password: <b>" . $password . "</b></div></div>", "", "black");
                    header("Location:login.php");
                    exit;
                } else {
                    $app->setpesan("Siswa gagal diupdate", "", "red");
                }
            } else {
                $app->setpesan("User sudah teraftar!", "red");
            }
        } else {
            $app->setpesan("User sudah teraftar!", "red");
        }
    } else {
        $app->setpesan("Pastikan NIS dan Nama Siswa terdaftar!", "red");
    }
}
header("Location:register.php");
