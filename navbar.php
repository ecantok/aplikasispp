<?php 
require_once 'app.php';
if ($selectedUrl == "navbar.php") {
  //Don't come here
  header("location:index.php");
}
?>
  <nav class="navbar">

    <?php
      if ($app->cekPemissionLevel($levelUser)):
    ?>
    <a href="index.php" class="nav-link<?=($selectedUrl == "index.php")? " selected": "" ; ?>">Home</a>
    <a href="petugas.php" class="nav-link<?=($selectedUrl == "petugas.php")? " selected": "" ; ?>">Data Petugas</a>
    <a href="spp.php" class="nav-link<?=($selectedUrl == "spp.php")? " selected": "" ; ?>">Data SPP</a>
    <a href="kelas.php" class="nav-link<?=($selectedUrl == "kelas.php")? " selected": "" ; ?>">Data Kelas</a>
    <a href="siswa.php" class="nav-link<?=($selectedUrl == "siswa.php")? " selected": "" ; ?>">Data Siswa</a>
    <a href="sppsiswa.php" class="nav-link<?=($selectedUrl == "sppsiswa.php")? " selected": "" ; ?>">SPP Siswa</a>
    <?php endif; if($app->cekPemissionLevel($levelUser,"Siswa") === false): ?>
    <a href="pembayaran.php" class="nav-link<?=($selectedUrl == "pembayaran.php")? " selected": "" ; ?>">Entri Pembayaran</a>
    <?php endif; ?>
    <a href="history.php" class="nav-link<?=($selectedUrl == "history.php")? " selected": "" ; ?>">History Pembayaran</a>
    <?php if ($app->cekPemissionLevel($levelUser)): ?>
    <a href="laporan.php" class="nav-link<?=($selectedUrl == "laporan.php")? " selected": "" ; ?>">Laporan</a>
    <?php endif; ?>
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
<?php ?>