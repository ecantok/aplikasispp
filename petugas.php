<?php
require_once 'App.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session || $app->cekPemissionLevel($levelUser) === false) {
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
    <title>Data Petugas || Pembayaran SPP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="datatable/datatables.min.css">
    <script src="datatable/jquery-3.6.0.min.js"></script>
    <script src="datatable/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#table").DataTable({
                "lengthChange": false,
                "pageLength": 30
            });
        });
    </script>
</head>

<body>
    <?php include_once "navbar.php"; ?>
    <div class="container">
        <?php
        $stmt = $conn->query("SELECT tbpetugas.*, tblogin.Username FROM `tbpetugas`
    LEFT JOIN tblogin ON tblogin.kode_petugas = tbpetugas.KodePetugas");
        ?>
        <h2>Data Petugas</h2>
        <div>
            <?php $app->pesanDialog(); ?>
        </div>
        <div class="mb">
            <button id="tampilModal" class="button">Tambah Data Petugas</button>
        </div>
        <div id="tableId" style="overflow-x:auto;">
            <table class="table-view" id="table">
                <thead>
                    <th>Kode Petugas</th>
                    <th>Nama Petugas</th>
                    <th>Username</th>
                    <th style="width: 200px;">Alamat</th>
                    <th style="width: 150px;">Telp</th>
                    <th style="width: 100px;">Jabatan</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) : ?>
                        <tr>
                            <td><?= $row['KodePetugas'] ?></td>
                            <td id="nama<?= $row['KodePetugas'] ?>"><?= $row['NamaPetugas'] ?></td>
                            <td><?= $row['Username'] ?></td>
                            <td><?= $row['Alamat'] ?></td>
                            <td><?= $row['Telp'] ?></td>
                            <td style="width: auto;"><?= $row['Jabatan'] ?></td>
                            <td>
                                <span><button class="blue" onclick="editPetugas('<?= $row['KodePetugas'] ?>')">Edit</button></span>
                                <?php if ($row['KodePetugas'] != 1) { ?>
                                    <span><button class="red" onclick="deletePetugas('<?= $row['KodePetugas'] ?>')">Delete</button></span>
                                <?php } ?>
                            </td>
                        </tr>
                        </span>
                        </li>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div id="respon"></div>
        </div>

        <!-- MODAL BOX -->
        <div class="modal" id="modalBox">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3><span id="modal-title">Tambah Data</span> </h3>
                <form id="formModal" action="tambahpetugas.php" method="post">
                    <input type="hidden" name="id" id="id">
                    <label for="kodepetugas"><b>Kode Petugas</b></label>
                    <input type="number" placeholder="Masukkan Kode Petugas" id="kodepetugas" name="kodepetugas" required>
                    <label for="NamaPetugas"><b>Nama Petugas</b></label>
                    <input type="text" placeholder="Masukkan Nama Petugas" id="NamaPetugas" name="NamaPetugas" required>
                    <label for="Username"><b>Username</b></label>
                    <input type="text" placeholder="Masukkan Username untuk Login" id="Username" name="Username" required>
                    <div class="flex">
                        <div style="width: 45%;margin-right: 5%;">
                            <label for="Password"><b>Password</b></label>
                            <input type="password" placeholder="Masukkan Password untuk Login" name="Password">
                        </div>
                        <div style="width: 50%;">
                            <label for="Password2"><b>Konfirmasi Password</b></label>
                            <input type="password" placeholder="Masukkan Password Konfirmasi" name="Password2">
                        </div>
                    </div>
                    <div style="margin-top: -10px; margin-bottom: 10px;">
                        <small class="info">Apabila password dibiarkan kosong, Password tidak diedit</small>
                    </div>
                    <label for="Alamat"><b>Alamat</b></label>
                    <input type="text" placeholder="Masukkan Alamat" name="Alamat" id="Alamat">
                    <label for="Telp"><b>Telp</b></label>
                    <input type="text" placeholder="Masukkan Nomor Telp" name="Telp" id="Telp">
                    <label for="Jabatan"><b>Jabatan</b></label>
                    <input type="text" placeholder="Masukkan Jabatan" name="Jabatan" id="Jabatan" required>
                    <div class="middle">
                        <button class="form-button" id="tombolAksi" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once "footer.php"; ?>
</body>
<script src="navbar.js"></script>
<script src="script.js"></script>

</html>