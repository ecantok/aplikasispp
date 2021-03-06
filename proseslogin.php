<?php
if (!empty($_POST)) {
  require_once 'App.php';
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Siapkan dan jalankan query
    $stmt = $conn->prepare("SELECT log.*, tbsiswa.NamaSiswa, tbpetugas.NamaPetugas FROM tblogin log LEFT JOIN tbsiswa on tbsiswa.NIS = log.nis_siswa LEFT JOIN tbpetugas on tbpetugas.KodePetugas = log.kode_petugas WHERE Username = :user AND `Password` = md5(:pass)");
    $stmt->execute(array(
      ":user" => $username,
      ":pass" => $password
    ));

    // Mengambil hasil dari query yang dijalankan

    // Validasi
    if ($stmt->rowCount() > 0) {

      //Mengambil data
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      
      // Memulai session dan Mengambil Nama berdasarkan Username
      
      if ($data['Level'] == "Siswa") {
        $_SESSION['bioname'] = $data["NamaSiswa"];
        $_SESSION['iduser'] = $data['nis_siswa'];
      } else if ($data['Level'] == "Petugas" || $data['Level'] == "Admin") {
        $_SESSION['bioname'] = $data["NamaPetugas"];
        $_SESSION['iduser'] = $data["kode_petugas"];
      } else {
        $app->setpesan("Maaf Anda tidak terdaftar!","","red");
        header("Location: index.php");
        exit;
      }
      $_SESSION['username'] = $data['Username'];
      $_SESSION['level'] = $data['Level'];
      header("Location: index.php");
      exit;

    }
    $app->setpesan("Username/Password salah!","","red");
    header("Location: login.php");
} else {
  header("Location:login.php") ;
}
