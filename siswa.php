<?php
require_once 'app.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session || $app->cekPemissionLevel($levelUser) === false) {
    header("Location:index.php");
    exit;
}
// $query = ("SELECT tbkelas.*, tbspp.TahunAjaran FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP");
// $resultKelas = $conn->query($query);
// $cekKelas = ($resultKelas -> num_rows == 0);
// if ($cekKelas) {
//   $app->setpesan("Mohon Masukan Data Kelas Terlebih Dahulu");
//   header("Location: kelas.php");
// }
$result = $conn->query("SELECT tbsiswa.*, tblogin.Username FROM `tbsiswa` LEFT JOIN tblogin ON tblogin.nis_siswa = tbsiswa.NIS
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa || Pembayaran SPP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="datatable/datatables.min.css">
    <script src="datatable/jquery-3.6.0.min.js"></script>
    <script src="datatable/datatables.min.js"></script>
</head>

<body>
    <?php include_once 'navbar.php' ?>
    <div class="container">

        <h2>Data Siswa</h2>
        <div class="mb">
            <button id="tampilModal" class="button">Tambah Data Siswa</button>
        </div>
        <div>
            <?php $app->pesanDialog(); ?>
        </div>
        <div id="tableId" style="overflow-x:auto;">
            <table class="table-view" id="table">
                <thead>
                    <th>No.</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    <th>User Terdaftar</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php $i = 1;
                    while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $row['NIS'] ?></td>
                            <td><?= $row['NamaSiswa'] ?></td>
                            <td><?= ($row['Alamat'] != '') ? $row['Alamat'] : '<div style="text-align: center;">-</div>'; ?></td>
                            <td><?= ($row['NoTelp'] != '') ? $row['NoTelp'] : '<div style="text-align: center;">-</div>'; ?></td>
                            <td style="text-align: center;"><?php
                                                            echo ($row['Username'] == "") ? "Belum" : "Terdaftar";
                                                            ?></td>
                            <td>
                                <span><button class="blue" onclick="editSiswa('<?= $row['NIS'] ?>')">Edit</button></span>
                                <span><button class="red" onclick="deleteSiswa(<?= $row['NIS'] ?>)">Delete</button></span>
                            </td>
                        </tr>
                    <?php $i++;
                    endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- MODAL BOX -->
        <div class="modal" id="modalBox">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h4><span id="modal-title">Tambah Data</span> Siswa</h4>
                <form id="formModal" action="prosestambahsiswa.php" method="post">
                    <input type="hidden" id="hiddenNis" name="hiddenNis" value="">
                    <label for="NIS"><b>NIS</b></label>
                    <input type="number" placeholder="Masukkan NIS" name="NIS" id="nis" required>

                    <label for="nama"><b>Nama Lengkap</b></label>
                    <input type="text" placeholder="Masukkan Nama Lengkap" id="nama" name="nama" required>

                    <label for="alamat"><b>Alamat</b></label>
                    <input type="text" placeholder="Masukkan Alamat" id="alamat" name="alamat">

                    <label for="telp"><b>No Telp</b></label>
                    <input type="text" placeholder="Masukkan Telp" id="telp" name="telp" maxlength="15">

                    <!-- <label for="kelas"><b>Kelas</b></label>
          <select id="kelas" name="kelas">
            <?php //while($dataKelas = $resultKelas->fetch_assoc()): 
            ?>
            <option value="<?//=$dataKelas['KodeKelas']?>"><?//=$dataKelas['TahunAjaran'] ?> | <?//= $dataKelas['NamaKelas'] ?></option>
            <?php //endwhile; 
            ?>
          </select> -->
                    <div class="middle">
                        <button class="form-button" id="tombolAksi" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal" id="modalConfirmDelete">
            <div class="modal-confirm-content">
                <span class="close">&times;</span>
                <h3><span id="modal-title-delete">Konfirmasi Hapus</span></h3>
                <hr>
                <p id="textConfirmDelete"></p>
                <form action="deletesiswa.php" method="get">
                    <input type="hidden" name="nis" id="deleteNis">
                    <input class="reset form-button" name="action" type="submit" value="Hapus User">
                    <input class="reset-darker form-button" name="action" type="submit" value="Hapus Siswa">
                </form>
            </div>
        </div>
    </div>
    <?php require_once "footer.php"; ?>
</body>
<script src="script.js"></script>
<script>
    $(document).ready(function() {
        $("#table").DataTable({
            // "ordering": false,
            // "info":     false
            "lengthChange": false,
            "pageLength": 30
        });
    });
</script>

</html>