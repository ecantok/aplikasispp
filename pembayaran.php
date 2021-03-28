<?php
require_once 'app.php';
require_once 'navbar.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session||$app->cekPemissionLevel($levelUser,"Siswa")) {
  header("Location:index.php");
  exit;
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entri Pembayaran || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'navbar.php'; ?>
<div class="container">
    
    <h2>Entri Pembayaran Spp</h2>
    <div>
      <?php 
        $app->pesanDialog();
      ?>
        
    </div>
    <div style="display: none;">
      <button id="tampilModal">Tambah Data Kelas</button>
    </div>
    
    <form action="" method="get">
      <div class="flex">
        <div>
          <label for="nis"><b>NIS</b></label>
          <?php $nis = (!empty($_GET['nis'])&& $_GET['nis'] != '')? $_GET['nis']: ''; ?>
          <input type="text" name="nis" id="nis" value="<?=$nis?>">

        </div>

        <input style="margin: 15px 10px;" type="submit" value="Cari">
      </div>
    </form>
    <hr>
    <?php
      if (!empty($_GET['nis'])&& $_GET['nis'] != '') {
    ?>
  <br>
  <?php
    $q = "SELECT tbspp.TahunAjaran, tbsppsiswa.kode_spp_siswa FROM tbsiswa 
    JOIN tbsppsiswa ON tbsiswa.NIS = tbsppsiswa.nis
    JOIN tbkelas ON tbkelas.KodeKelas = tbsppsiswa.kodekelas
    JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP
    WHERE tbsppsiswa.nis = ?
    ";
    $stmtTahunAjaran=$conn->prepare($q);
    $stmtTahunAjaran->bind_param("i", $nis);
    $stmtTahunAjaran->execute();
    $result = $stmtTahunAjaran->get_result();
    if ($result->num_rows > 0) {
    $result->fetch_all(MYSQLI_ASSOC);
    ?> 
    <select name='tahunajaran' id="tahunajaran" required>
    <option value="">-Tahun Ajaran-</option>
    <?php
    foreach ($result as $data ) {
      if (isset($_GET['kodespp'])&&!empty($_GET['kodespp']) && $_GET['kodespp']==$data['kode_spp_siswa']) {
        echo "
        <option value=".$data['kode_spp_siswa']." selected>".$data['TahunAjaran']."</option>
        ";
      } else {
        echo "
        <option value=".$data['kode_spp_siswa'].">".$data['TahunAjaran']."</option>
        ";
      }
    }
    echo "</select></td>";
    } else {
      ?>
        <p>Siswa tidak ditemukan</p>
      <?php
    }
  ?>
  <?php } ?>
  <div id="respon"></div>
  <p><i>Pembayaran SPP dilakukan dengan cara Mencari Tagihan Siswa dengan NIS melalui form diatas, kemudian dilakukan entri pembayaran</i></p>
</div>
  <?php require_once "footer.php";?>
  <span class="close"></span>
</body>
<script src="script.js"></script>
<script>
  const selectTh = document.getElementById("tahunajaran");
  selectTh.addEventListener('change', (event)=> {
      console.log(event);
      kodespp = event.target.value;
      addr = "getdataspp.php";
      if (kodespp == "") {
        document.getElementById("tabel").innerHTML = "Data belum dipilih";
        return;
      } else {
        if (window.XMLHttpRequest) {
          //Browser untuk IE7+, Firefox, Chrome, and Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          //Browser untuk IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function(){
          if (this.readyState == 4 & this.status == 200) {
            document.getElementById("respon").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET",""+addr+"?q="+kodespp,true);
        xmlhttp.send();
      }
    });
</script>
</html>