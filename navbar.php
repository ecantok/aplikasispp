<?php 
require_once 'app.php';
function nav($nav = "home"){
  global $levelUser;
  global $user;
  global $app;
  
?>
  <nav class="navbar">
      
    <?php
    if ($nav == "home") {
      echo '
        <a class="nav-link selected" href="" class="nav-link">Home</a>
      ';
    } else {
      echo '
        <a href="index.php" class="nav-link">Home</a>
      ';
    } 
    $link = ['spp','kelas','siswa','petugas','pembayaran','history','laporan'];
    $jumlahlink = count($link);
    for ($i=0; $i < $jumlahlink; $i++) { 
      if ($app->cekPemissionLevel($levelUser,'Petugas')||$app->cekPemissionLevel($levelUser,"Siswa")) {
        if ($i <= 3||$i == 6) {
          continue;
        } 
        if ($app->cekPemissionLevel($levelUser, "Siswa")) {
          if ($i == 4) {
            continue;
          }  
        }
      }  
      if ($nav == $link[$i]) {
        echo '
          <a class="nav-link selected" href="">'.ucfirst($link[$i]).'</a>
        ';
      } else {
        echo '
          <a href="'.$link[$i].'.php" class="nav-link">'.ucfirst($link[$i]).'</a>
        ';
      }
    }
     ?>
        <a href="logout.php" class="nav-link">Logout</a>
  </nav>
  <ul class="navtop">
    <li class="navtop-item">
      Aplikasi Pembayaran SPP
    </li>
    <li class="navtop-item">
      <?=$user?>
    </li>
  </ul>
<?php } ?>