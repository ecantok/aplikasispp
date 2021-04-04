<?php
require_once 'app.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session||$app->cekPemissionLevel($levelUser,"Siswa")) {
  header("Location:index.php");
  exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    @$idtransaksi = $_POST["idtransaksi"];
    @$kodepembayaran = intval($_POST["kodepembayaran"]);
    @$jumlah_bayaran = doubleval($_POST["bayar"]);
    @$metode_transaksi = $_POST["metode_transaksi"];
    @$kembalian = doubleval($_POST["kembalian"]);
    @$keterangan = $_POST["keterangan"];
    @$submit = $_POST["submit"];
    $param = "";
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
      $param = "?kodepembayaran={$kodepembayaran}";
      $conn->autocommit(0);
      $conn->begin_transaction();
      if ($submit == "Bayar") {
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
      } elseif ($submit == "Edit") {
        try {
          $stmt = $conn->prepare("UPDATE `tbtransaksi` SET 
          `kodepetugas`= ?,
          `jumlah_bayaran`= ?,
          `metode_transaksi`= ?,
          `kembalian`= ?,
          `keterangan`= ?,
          `tgl_transaksi`= current_timestamp() WHERE idtransaksi = ?");
          $stmt->bind_param("idsdss", $idUser, $jumlah_bayaran, $metode_transaksi, $kembalian, $keterangan, $idtransaksi);
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
      } else {
        $app->setpesan("Pastikan data submit sudah Terisi!");
      }
    } else {
      $app->setpesan("Pastikan data sudah Terisi!");
    }
}
header("location:datasppsiswa.php{$param}");
