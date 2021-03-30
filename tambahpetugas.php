<?php
require_once 'app.php';
  if (!empty($_POST)) {
    $KodePetugas = $_POST['kodepetugas'];
    $NamaPetugas = $_POST['NamaPetugas'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Password2 = $_POST['Password2'];
    $Alamat = $_POST['Alamat'];
    $Telp = $_POST['Telp'];
    $Jabatan = $_POST['Jabatan'];
    $cekPass = $app->konfirmasiPassword($Password,$Password2);


    $stmtCek = $conn->prepare("SELECT KodePetugas FROM tbpetugas WHERE KodePetugas = ?");
    $stmtCek->bind_param('s',$KodePetugas);
    $stmtCek->execute();
    $resultCek = $stmtCek->get_result();
    if ($cekPass) {
      if ($Password != '' && $Password2 != '') {
        if ($resultCek->num_rows == 0){
          $stmtCek->close();
          unset($stmtCek);
          $query = "INSERT INTO `tbpetugas`(`KodePetugas`, `NamaPetugas`, `Alamat`, `Telp`, `Jabatan`) VALUES (?,?,?,?,?)";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("sssss",$KodePetugas,$NamaPetugas,$Alamat,$Telp,$Jabatan);
          $stmt->execute();
          if ($conn->affected_rows > 0) {
            $stmt->close();
            $result = $conn->query("SELECT KodePetugas FROM tbpetugas WHERE KodePetugas = {$KodePetugas}");
            if ($result->num_rows == 1) {
              unset($stmt);
  
              $level = "Petugas";
              $query = "INSERT INTO `tblogin` (`KodeLogin`, `Username`, `Password`, `Level`, `nis_siswa`, `kode_petugas`) VALUES (NULL, ?, MD5(?), ?, NULL, ?)";
              $stmt2 = $conn->prepare($query);
              $stmt2->bind_param('ssss', $Username, $Password, $level, $KodePetugas);
              if ($conn->errno == 0) {
                $stmt2->execute();
                if ($conn->affected_rows > 0) {
                  $app->setpesan("Petugas Berhasil","ditambahkan");
                } else {
                  var_dump($conn->error); die;
                  $app->setpesan("Login Petugas Gagal", "ditambahkan");
                }
              } else {
                $app->setpesan("Login Petugas Gagal", "Dibuat");
              }
            } else {
              echo ":/";
            }
          } else {
            $app->setpesan("Petugas Gagal!", "Dibuat");
          }
        } else {
          $app->setpesan("ID Petugas tidak boleh sama!");
        }
      } else {
        $app->setpesan("Password Harap diisi!");
      }
    } else {
      $app->setpesan("Password Konfirmasi salah!");
    }
  }
  header("Location: petugas.php")
?>