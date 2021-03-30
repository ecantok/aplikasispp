<?php
require_once 'app.php';
require_once 'navbar.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session||$app->cekPemissionLevel($levelUser,"Siswa")) {
  header("Location:index.php");
  exit;
} 
$kondisi1 = !empty($_GET['buat'])&& $_GET['buat'] != '';
$kondisi2 = !empty($_GET['kodepembayaran'])&& $_GET['kodepembayaran'] != '';
$buat = null;
if ($kondisi1) {
  $buat = $_GET['buat'];
} else if ($kondisi2) {
  $buat = $_GET['kodepembayaran'];
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data SPP Siswa || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'navbar.php'; ?>
<div class="container">
    
    <h2>Data SPP Siswa</h2>
    <div>
      <?php 
        $app->pesanDialog();
      ?>
        
    </div>
    
    <form method="get">
      <div class="flex">
        <div>
          <label for="nis"><b>Buat Pembayaran</b></label>
          <input type="text" name="buat" id="buat" value="<?=$buat?>" <?= ($kondisi1 || $kondisi2)? 'readonly': ''; ?>>
        </div>
        <input style="margin: 15px 10px;" type="submit" value="Buat">
      </div>
    </form>
    <hr>
  <?php if ($buat !== null) { 
    $buat = explode("-", $buat);
    if (isset($buat[1])) {
      echo "true"; die;
    } else {
      if ($kondisi1) {
        $kodepembayaran = $_GET['buat'];
      } else if ($kondisi2) {
        $kodepembayaran = $_GET['kodepembayaran'];
      } else {
        echo "Terjadi eksalahan"; die;
      }
    }
    $query = "SELECT tbsiswa.NIS, tbsiswa.NamaSiswa, tbsppsiswa.kode_spp_siswa, tbkelas.NamaKelas, tbspp.TahunAjaran, tbspp.BesarBayaran, tbpembayaran.BulanDibayar, tbpembayaran.KodePembayaran, 
    COUNT(tbtransaksi.idtransaksi) AS cicilanke, (tbspp.BesarBayaran - SUM(tbtransaksi.jumlah_bayaran)) AS sisa_tunggakan
    FROM tbpembayaran JOIN tbsppsiswa ON tbsppsiswa.kode_spp_siswa = tbpembayaran.kode_spp_siswa JOIN tbsiswa ON tbsiswa.NIS = tbsppsiswa.nis JOIN tbkelas ON tbsppsiswa.Kodekelas = tbkelas.KodeKelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP
    LEFT JOIN tbtransaksi ON tbtransaksi.kodepembayaran = tbpembayaran.KodePembayaran WHERE tbpembayaran.KodePembayaran = ?";
    $stmtSiswa = $conn->prepare($query);
    $stmtSiswa->bind_param("s", $kodepembayaran);
    $stmtSiswa->execute();
    $resultSiswa = $stmtSiswa->get_result();
    $dataSiswa = $resultSiswa->fetch_assoc();
    if ($resultSiswa->num_rows > 0) {
      $tunggakan = ($dataSiswa['sisa_tunggakan'] === null)? $dataSiswa['BesarBayaran'] : $dataSiswa['sisa_tunggakan'];
    ?>
    <div style="overflow-x: auto;">
      <fieldset class="fieldset">
        <legend class="legend"><h3>Biodata Siswa</h3></legend>
        <table>
            <tr>
              <td>NIS</td>
              <td>:</td>
              <td><?= $dataSiswa['NIS'] ?></td>
            </tr>
            <tr>
              <td>Nama Siswa</td>
              <td>:</td>
              <td><?= $dataSiswa['NamaSiswa'] ?></td>
            </tr>
            <tr>
              <td>Kelas</td>
              <td>:</td>
              <td><?= $dataSiswa['NamaKelas'] ?></td>
            </tr>
            <tr>
              <td>Tahun Ajaran</td>
              <td>:</td>
              <td><?= $dataSiswa['TahunAjaran'] ?></td>
            </tr>
            <tr>
              <td>Jumlah Bayaran</td>
              <td>:</td>
              <td><?= "Rp.".$app->numberformat($dataSiswa['BesarBayaran']) ?></td>
            </tr>
        </table>
      </fieldset>
    </div>
    <hr>
    <div style="overflow-x: auto;">
      <fieldset style="max-width: 500px; padding-left: 20px; padding-right: 20px;">
        <legend>
          <h3>Pembayaran Spp</h3>
        </legend>
        <form method="post">
          <label for="idtransaksi">ID Transaksi</label>
          <input type="text" name="idtransaksi" id="idtransaksi" value="<?=$dataSiswa['KodePembayaran']."-".($dataSiswa['cicilanke']+1)?>" readonly>
          <label for="kodepembayaran">Kode Pembayaran</label>
          <input type="number" name="kodepembayaran" value="<?=$dataSiswa['KodePembayaran'] ?>" id="kodepembayaran" readonly>
          <label for="untukspp">Untuk Pembayaran SPP</label>
          <input type="text" name="untukspp" value="<?=$dataSiswa['BulanDibayar']." - ".$dataSiswa['TahunAjaran'] ?>" id="untukspp" readonly>
          <label for="petugas">Petugas</label>
          <input type="text" name="petugas" id="petugas" value="<?=$bioname?>" readonly>
          <label for="tunggakkan">Tunggakkan</label>
          <input type="text" name="tunggakkan" value="<?=$app->numberformat($tunggakan)?>" readonly>
          <input type="hidden" name="tunggakan" id="tunggakkan" value="<?=$tunggakan ?>">
          <label for="bayar">Bayar</label>
          <input type="number" name="bayar" id="bayar" onkeyup="cek_kembalian()">
          <label for="sisa" id="change">Sisa Tunggakan</label>
          <input type="number" name="sisa" id="sisa" readonly>
          <label for="metode_transaksi" id="change">Metode Pembayaran</label>
          <input type="text" name="metode_transaksi" id="metode_transaksi" maxlength="20">
          <input type="hidden" name="kembalian" id="kembalian">
          <label for="keterangan">Keteragan</label>
          <textarea style="resize: vertical;" name="keterangan" id="keterangan" cols="30" rows="10"></textarea>
          <div class="middle">
            <input type="submit" value="Bayar" class="form-button">
          </div>
        </form>
      </fieldset>
    </div>
    <hr>
    <p><i>Pembayaran SPP dilakukan dengan cara Mencari Tagihan Siswa dengan NIS melalui form diatas, lalu dilakukan transaksi pembayaran</i></p>
    <?php } else {
      echo "<p>Data tidak ditemukan.</p> ";
    }
  }
    ?>
</div>
  <?php require_once "footer.php";?>
</body>
<script>
  const bayar = document.getElementById('bayar');
  bayar.addEventListener('change', (event)=>{
    cek_kembalian();
  });
  function cek_kembalian() {
    const bayar = document.getElementById('bayar').value;
    const total_biaya = document.getElementById('tunggakkan').value;
    const change = document.getElementById('change');
    const kembalian = document.getElementById('kembalian');
    let sisa = bayar - total_biaya;
    kembalian.value = sisa;
    change.innerHTML = "Kembalian";
    if (sisa <= -1) {
      change.innerHTML = "Sisa Tunggakan";
      kembalian.value = 0;
    }
    sisa = Math.abs(sisa);
    document.getElementById("sisa").value = sisa
    
  }
</script>
</html>
<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    @$idtransaksi = $_POST["idtransaksi"];
    @$kodepembayaran = intval($_POST["kodepembayaran"]);
    @$jumlah_bayaran = doubleval($_POST["bayar"]);
    @$metode_transaksi = $_POST["metode_transaksi"];
    @$kembalian = doubleval($_POST["kembalian"]);
    @$keterangan = $_POST["keterangan"];
    // var_dump($_POST);
    $verifikasi = 
    (is_null($idtransaksi) && 
    is_null($kodepembayaran) && 
    is_null($jumlah_bayaran) && 
    is_null($metode_transaksi) && 
    is_null($keterangan) && 
    is_null($kembalian)) === false
    ;
    if ($verifikasi) {
      $conn->autocommit(0);
      $conn->begin_transaction();
      try {
        $stmt = $conn->prepare("INSERT INTO `tbtransaksi` (`idtransaksi`, `kodepembayaran`, `kodepetugas`, `jumlah_bayaran`, `metode_transaksi`, `kembalian`, `keterangan`, `tgl_transaksi`) VALUES (?, ?, ?, ?, ?, ?, ?, current_timestamp())");
        $stmt->bind_param("siidsds", $idtransaksi, $kodepembayaran, $idUser, $jumlah_bayaran, $metode_transaksi, $kembalian, $keterangan);
        $stmt->execute();
        $stmt->close();

        // Jika tidak ada error sampai sini, dilakukan commit
        $conn->commit();
        $app->setpesan("Transaksi Berhasil Dilakukan!");
      } catch (mysqli_sql_exception $e) {
        //Batalkan
        $conn->rollback();
        $app->setpesan("Transaksi Gagal Dilakukan!");
        throw $e;
      }
    }
  }
?>