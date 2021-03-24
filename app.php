<?php
 //Koneksi SQL
 $conn = new mysqli('localhost', 'root', '', 'dbspprpl3');
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

  // Sekadar cek hak akses aku masih experiment ini...
  function cekPemissionLevel($levelUser, $level = 'Admin'){
    if ($levelUser == $level) {
      return true;
    }
    return false;
  }
  
  function konfirmasiPassword($password1, $password2){
    if ($password1 == $password2) {
      return true;
    }
    return false;
  }

 //Format Angka 
 function numberformat($number){
    return number_format($number,0,',','.');
 }
  
 //Format String
 function generateRandomString($length = 6){
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

function setPesanDirect($pesan)
{
  $_SESSION['pesandirect'] = $pesan;
}
//Format Pesan Alert Dialog
function pesanDirect(){
  if (!empty($_SESSION['pesandirect'])) {
  echo'
  <script defer>alert("'.$_SESSION['pesandirect'].'")</script>';
  unset($_SESSION['pesandirect']);
  }
}

//Format Pesan Alert Dialog
 function pesanDialog(){
  if (!empty($_SESSION['flash'])) {
  echo'
  <script defer>alert("'.$_SESSION['flash']['pesan'].' '.$_SESSION['flash']['aksi'].'")</script>';
  unset($_SESSION['flash']);
  }
}

//Format Pesan
function pesan(){
  if (!empty($_SESSION['flash'])) {
  echo'
  <div style="color :'.$_SESSION['flash']['warna'].';">
  '.$_SESSION['flash']['pesan'].' '.$_SESSION['flash']['aksi'].'</div><br>';
  unset($_SESSION['flash']);
  }
}
$bulan = [
  "Juli","Agustus","September","Oktober","November","Desember", "Januari","Februari","Maret",
"April","Mei","Juni"];
function buatBulan($selected = "")
{
  global $bulan;
  echo '<optgroup label="Semester 1">';
  for ($i=0; $i < 12; $i++) { 
    if ($bulan[$i] == $selected) {
      echo "<option value=".$bulan[$i]." selected>".$bulan[$i]."</option>";
    } else {
      echo "<option value=".$bulan[$i].">".$bulan[$i]."</option>";
    } if ($i == 5) {
      echo '</optgroup>';
      echo '<optgroup label="Semester 2">';
    }
  }
  echo '</optgroup>';
}

function buatTahunAjaran($selected = "")
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
?>