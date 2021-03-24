<?php
require_once 'app.php';
require_once 'navbar.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session||cekPemissionLevel($levelUser)===false) {
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
  <title>Data Spp || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php  nav("spp") ?>
<div class="container">
    <?php 
    $stmt = $conn ->prepare("SELECT * FROM tbspp ORDER BY TahunAjaran ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <h2>Data Spp</h2>
    <div>
      <?php pesanDialog();?>
    </div>
    <div class="mb">
      <button id="tampilModal" class="button">Tambah Data SPP</button>
    </div>
    <div id="tableId" style="overflow-x:auto;">
      <table class="table-view">
        <thead>
          <th>No</th>
          <th>Tahun Ajaran</th>
          <th>Tingkat</th>
          <th>Besar Bayaran</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php $i = 1; while ($row = $result ->fetch_assoc() ):?>
            <tr>
              <td><?=$i?></td>
              <td><?=$row['TahunAjaran']?></td>
              <td><?=$row['Tingkat'] ?></td>
              <td><?=$row['BesarBayaran'] ?></td>
              <td>
                <span><button class="blue" onclick="editSpp('<?=$row['KodeSPP'] ?>')">Edit</button></span>
                <span><button class="red" onclick="deleteSpp('<?= $row['KodeSPP'] ?>')">Delete</button></span>
              </td>
            </tr>
          </span>
        </li>
          <?php $i++; endwhile; ?>
        </tbody>
      </table>
      <div id="respon"></div>
    </div>

    <!-- MODAL BOX -->
    <div class="modal" id="modalBox">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h3><span id="modal-title">Tambah Data</span> </h3>
        <form id="formModal" action="tambahspp.php" method="post">
            <input type="hidden" name="KodeSPP" id="KodeSPP">
          <label for="TahunAjaran"><b>Tahun Ajaran</b></label>
          <select id="TahunAjaran" name="TahunAjaran">
            <?php 
              buatTahunAjaran();
            ?>
          </select>
          <label for="Tingkat"><b>Tingkat</b></label>
          <input type="text" placeholder="Masukkan Tingkat Spp" id="Tingkat" name="Tingkat" required>
          <label for="BesarBayaran"><b>Besar Bayaran</b></label>
          <input type="number" placeholder="Masukkan Besar Bayaran" name="BesarBayaran" id="BesarBayaran" required>
          <button id="tombolAksi" type="submit">Simpan</button>
        </form>
      </div>
    </div>
    </div>
</body>
<script src="script.js"></script>
</html>