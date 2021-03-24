<?php
require_once 'app.php';
  if (!empty($_POST)) {
    
    $NamaPetugas = $_POST['NamaPetugas'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Password2 = $_POST['Password2'];
    $Alamat = $_POST['Alamat'];
    $Telp = $_POST['Telp'];
    $Jabatan = $_POST['Jabatan'];
    $cekPass = konfirmasiPassword($Password,$Password2);


    $stmtCek = $conn->prepare("SELECT KodePetugas FROM tbpetugas WHERE KodePetugas = ?");
    $stmtCek->bind_param('s',$KodePetugas);
    $stmtCek->execute();
    $resultCek = $stmtCek->get_result();
    if ($cekPass) {
      if ($Password != '' && $Password2 != '') {
        if ($resultCek->num_rows == 0){
          $stmtCek->close();
          $query = "INSERT INTO `tbpetugas`(`KodePetugas`, `Username`, `Password`, `NamaPetugas`, `Alamat`, `Telp`, `Jabatan`) VALUES (?,?,?,?,?,?,?)";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("sssssss",$KodePetugas,$Username,$Password,$NamaPetugas,$Alamat,$Telp,$Jabatan);
          $stmt->execute();
          
          if ($conn->affected_rows > 0) {
            $level = "Petugas";
            $query = "INSERT INTO `tblogin`(`KodeLogin`, `Username`, `Password`, `Level`) VALUES ('', ?, md5(?), ?)";
            $stmt2 = $conn->prepare($query);
            $stmt2->bind_param('sss', $Username, $Password, $level);
            $stmt2->execute();
            // die(var_dump($conn->affected_rows));
            if ($conn->affected_rows > 0) {
              setpesan("Petugas Berhasil","ditambahkan");
            } else {
              setpesan("Petugas Gagal", "ditambahkan");
            }
          } else {
            setpesan("Buatlah Username yang berbeda!");
          }
        } else {
          setpesan("ID Petugas tidak boleh sama!");
        }
      } else {
        setpesan("Password Harap diisi!");
      }
    } else {
      setpesan("Password Konfirmasi salah!");
    }
  }
  header("Location: petugas.php")
?>