<?php

  // URL
  $url = explode('/', $_SERVER['REQUEST_URI']);
  $selectedUrl = $url[2];

 //Koneksi SQL
  $conn = new mysqli('localhost', 'root', '', 'dbspp');
  if ($conn->connect_error) {
   die("Koneksi mysql error : ".$conn->connect_error);
  }
  
  // Session login dimulai false
  if (!session_id()) {
    session_start();
  }
  $session = false;
  
  // Jika ada session username dari proses login...
  if (!empty($_SESSION['username'])) {
    // ... ada session (session = true)
    $session = true;
    $idUser = $_SESSION['iduser'];
    $user = $_SESSION['username'];
    $levelUser = $_SESSION['level'];
  } 

  //Buat objek yang akan digunakan
  $app = new App();

  class App {
  // Cek Hak Akses
  public function cekPemissionLevel($levelUser, $level = 'Admin'){
    if ($levelUser == $level) {
      return true;
    }
    return false;
  }
  
  public function konfirmasiPassword($password1, $password2){
    if ($password1 == $password2) {
      return true;
    }
    return false;
  }

 //Format Angka 
 public function numberformat($number){
    return number_format($number,0,',','.');
 }
  
 //Format String
 public function generateRandomString($length = 6){
  $randomstring = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
  $password = substr(str_shuffle($randomstring),0,$length);
  return $password;
 }

 //Format Set Pesan
 function setpesan($pesan, $aksi = "", $warna = "green"){
  $_SESSION['flash'] = [
    'pesan' => $pesan,
    'aksi'  => $aksi,
    'warna' => $warna
  ];
}

public function setPesanDirect($pesan)
{
  $_SESSION['pesandirect'] = $pesan;
}
//Format Pesan Alert Dialog
public function pesanDirect(){
  if (!empty($_SESSION['pesandirect'])) {
  echo'
  <script defer>alert("'.$_SESSION['pesandirect'].'")</script>';
  unset($_SESSION['pesandirect']);
  }
}

//Format Pesan Alert Dialog
public function pesanDialog(){
  if (!empty($_SESSION['flash'])) {
  echo'
  <script defer>alert("'.$_SESSION['flash']['pesan'].' '.$_SESSION['flash']['aksi'].'")</script>';
  unset($_SESSION['flash']);
  }
}

//Format Pesan
public function pesan(){
  if (!empty($_SESSION['flash'])) {
  echo'
  <div style="color :'.$_SESSION['flash']['warna'].';">
  '.$_SESSION['flash']['pesan'].' '.$_SESSION['flash']['aksi'].'</div><br>';
  unset($_SESSION['flash']);
  }
}

public function returnForm(...$input)
{
  $_SESSION["returnForm"] = $input; 
}

public function cekReturnForm($returnedform)
{
  if (!empty($_SESSION["returnForm"])) {
    return $_SESSION["returnForm"];
  }
}

private $bulan = [
  "Juli","Agustus","September","Oktober","November","Desember", "Januari","Februari","Maret",
"April","Mei","Juni"];

  public function getBulan()
  {
    return $this->bulan;
  }

function buatBulan($selected = "")
{
  
  echo '<optgroup label="Semester 1">';
  for ($i=0; $i < 12; $i++) { 
    if ($this->bulan[$i] == $selected) {
      echo "<option value=".$this->bulan[$i]." selected>".$this->bulan[$i]."</option>";
    } else {
      echo "<option value=".$this->bulan[$i].">".$this->bulan[$i]."</option>";
    } if ($i == 5) {
      echo '</optgroup>';
      echo '<optgroup label="Semester 2">';
    }
  }
  echo '</optgroup>';
}

public function buatTahunAjaran($selected = "")
{
  $tahunajaran = ["2020/2021","2021/2022","2022/2023","2023/2024","2024/2025"];
  for($i = 0; $i < count($tahunajaran); $i++){
    if ($tahunajaran[$i] == $selected) {
      echo "<option value=".$tahunajaran[$i]." selected>".$tahunajaran[$i]."</option>";
    } else {
      echo "<option value=".$tahunajaran[$i].">".$tahunajaran[$i]."</option>";
    }
  } 
}
  }
?>