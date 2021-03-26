<?php
if (!empty($_POST)) {
  require_once 'app.php';
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Siapkan dan jalankan query
    $stmt = $conn->prepare("SELECT log.KodeLogin, log.Username, log.Level FROM tblogin log WHERE Username = ? AND `Password` = md5(?)");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();

    // Mengambil hasil dari query yang dijalankan
    $result = $stmt->get_result();

    // Validasi
    if ($result->num_rows > 0) {

      //Mengambil data
      $data = mysqli_fetch_assoc($result);
      
      // Memulai session dan Mengambil Nama berdasarkan Username
      if ($data['Level'] == "Siswa") {
        $_SESSION['username'] = getUsername("siswa","NIS = ?");
        $_SESSION['iduser'] = $data['Username'];
      } else if ($data['Level'] == "Petugas") {
        $_SESSION['username'] = $data['Username'];
        $_SESSION['iduser'] = $data['KodeLogin'];
      } else if ($data['Level'] == "Admin") {
        $_SESSION['username'] = $data['Username'];
        $_SESSION['iduser'] = $data['KodeLogin'];
      } else {
        $app->setpesan("Maaf Anda tidak terdaftar!","","red");
        header("Location: index.php");
        exit;
      }
      $_SESSION['level'] = $data['Level'];
      header("Location: index.php");
      exit;

    }
    $app->setpesan("Username/Password salah!","","red");
    header("Location: login.php");
} else {
  header("Location:login.php") ;
}
function getUsername($nama,$param){
  global $conn;
  global $username;
  $namaCaps = "Nama".ucfirst($nama);
  $query = "SELECT {$namaCaps} FROM tb{$nama} WHERE {$param}";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s",$username);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  return $data[$namaCaps];
}
?>