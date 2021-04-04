<?php
require_once 'app.php';
require_once 'navbar.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session||$app->cekPemissionLevel($levelUser)===false) {
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
  <title>Data Kelas || Pembayaran SPP</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="datatable/datatables.min.css">
  <script src="datatable/jquery-3.6.0.min.js"></script>
  <script src="datatable/datatables.min.js"></script>
  <script>
  $(document).ready(function () {
    $("#table").DataTable({
      "lengthChange": false,
      "pageLength" : 30
    });
  });
</script>
</head>
<body>
<?php require_once 'navbar.php'; ?>
  <div class="container">
    <?php 
    $stmt = $conn ->prepare("SELECT tbkelas.*, tbspp.TahunAjaran, tbspp.Tingkat FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP");
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <h2>Data Kelas</h2>
    <div>
      <?php $app->pesanDialog(); $app->pesanDirect();?>
    </div>
    <div class="mb">
      <button id="tampilModal" class="button">Tambah Data Kelas</button>
    </div>
    <div id="tableId" style="overflow-x:auto;">
      <table class="table-view" id="table">
        <thead>
          <th>No.</th>
          <th>TahunAjaran</th>
          <th>Tingkat</th>
          <th>Jurusan</th>
          <th>Nama Kelas</th>
          <th>Action</th>
        </thead>
        <tbody>
          <?php $i=1; while ($row = $result ->fetch_assoc() ):?>
            <tr>
              <td><?=$i ?></td>
              <td><?=$row['TahunAjaran'] ?></td>
              <td><?=$row['Tingkat']?></td>
              <td><?=$row['Jurusan'] ?></td>
              <td><?=$row['NamaKelas'] ?></td>
              <td>
                <span><button class="blue" onclick="editKelas('<?=$row['KodeKelas'] ?>')">Edit</button></span>
                <span><button class="red" onclick="deleteKelas('<?= $row['KodeKelas'] ?>','<?=$row['NamaKelas'] ?>')">Delete</button></span>
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
        <h4><span id="modal-title">Tambah Data Kelas</span> </h4>
        <form id="formModal" action="tambahkelas.php" method="post">
          <input type="hidden" name="KodeKelas" id="KodeKelas" value="">
          <label for="KodeSPP"><b>Tingkat </b></label>
          <select name="KodeSPP" id="KodeSPP" required>
            <?php 
            $resultSPP = $conn->query("SELECT * FROM tbspp");
            while($dataSPP = $resultSPP->fetch_assoc()): ?>
            <option value="<?=$dataSPP['KodeSPP']?>"><?=$dataSPP['TahunAjaran'] ?> | <?= $dataSPP['Tingkat'] ?></option>
            <?php endwhile; ?>
          </select>
          <label for="Jurusan"><b>Jurusan</b></label>
          <input type="text" placeholder="Masukkan Jurusan" id="Jurusan" name="Jurusan">
          <label for="NamaKelas"><b>Nama Kelas</b></label>
          <input type="text" placeholder="Masukkan Nama Kelas" name="NamaKelas" id="NamaKelas" required>
          <div class="middle">
            <button class="form-button" id="tombolAksi" type="submit">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php require_once 'footer.php' ?>
</body>
<script src="script.js"></script>
<script src="navbar.js"></script>
</html>