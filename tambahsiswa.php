<?php
require_once 'app.php';
  if (!empty($_POST)) {
    $nis = $_POST['NIS'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $kelas = $_POST['kelas'];

    $stmtCek = $conn->prepare("SELECT NIS FROM tbsiswa WHERE NIS = ?");
    $stmtCek->bind_param('s',$nis);
    $stmtCek->execute();
    $resultCek = $stmtCek->get_result();
    if ($resultCek->num_rows == 0){
      $stmtCek->close();
      $query = "INSERT INTO `tbsiswa` (`NIS`, `Username`, `Password`, `NamaSiswa`, `Alamat`, `NoTelp`, `Kodekelas`) VALUES (?, '', '', ?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssss",$nis,$nama,$alamat,$telp,$kelas);
      if ($stmt->errno == 0) {
        $stmtKelas = $conn->prepare("SELECT tbkelas.*, tbspp.TahunAjaran FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP WHERE KodeKelas = ?");
        $stmtKelas->bind_param("s",$kelas);
        $stmtKelas->execute();
        $resultKelas =$stmtKelas->get_result();
        $dataKelas = $resultKelas->fetch_assoc();
        $semester = explode('/',$dataKelas['TahunAjaran']);
        $selectedSemester = null;
        
        
        if ($conn->errno) {
          die($conn->error);
        }
        
        $stmt->execute();
        
        for ($i=0; $i < 12; $i++) { 
          if (array_search($bulan[$i],$bulan)<6) {
            $selectedSemester = $semester[0];
          } else {
            $selectedSemester = $semester[1];
          }
          $stmtCreateSpp = $conn -> prepare("INSERT INTO `tbpembayaran`(`KodePembayaran`, `KodePetugas`, `NIS`, `TglPembayaran`, `BulanDibayar`, `TahunDibayar`, `StatusPembayaran`) VALUES ('', ?, ?, '', ?, ?, '-')");
          $stmtCreateSpp ->bind_param('ssss', $idUser, $nis,$bulan[$i],$selectedSemester);
          
          $stmtCreateSpp -> execute();
          if ($stmtCreateSpp->error) {
            (var_dump($stmtCreateSpp)."<br>");
          }
          $stmtCreateSpp->close();
          unset($stmtCreateSpp);
        }
        
        setpesan("Siswa Berhasil","ditambahkan");
      } else {
        setpesan("Siswa Gagal", "ditambahkan");
      }
    } else {
      setpesan("NIS tidak boleh sama!");
    }
  }
  header("Location: siswa.php")
?>