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
    <title>Data Spp || Pembayaran SPP</title>
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
    <?php require_once 'navbar.php'; ?>
    <div class="container">
        <?php
        $stmt = $conn->query("SELECT * FROM tbspp ORDER BY TahunAjaran ASC");
        ?>
        <h2>Data Spp</h2>
        <div>
            <?php $app->pesanDialog(); ?>
        </div>
        <div class="mb">
            <button id="tampilModal" class="button">Tambah Data SPP</button>
        </div>
        <div id="tableId" style="overflow-x:auto;">
            <table class="table-view" width="100%" id="table">
                <thead>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Tingkat</th>
                    <th>Besar Bayaran</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) : ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $row['TahunAjaran'] ?></td>
                            <td><?= $row['Tingkat'] ?></td>
                            <td><?= $row['BesarBayaran'] ?></td>
                            <td>
                                <span><button class="blue" onclick="editSpp('<?= $row['KodeSPP'] ?>')">Edit</button></span>
                                <span><button class="red" onclick="deleteSpp('<?= $row['KodeSPP'] ?>')">Delete</button></span>
                            </td>
                        </tr>
                        </span>
                        </li>
                    <?php $i++;
                    endforeach; ?>
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
                        $app->buatTahunAjaran();
                        ?>
                    </select>
                    <label for="Tingkat"><b>Tingkat</b></label>
                    <input type="text" placeholder="Masukkan Tingkat Spp" id="Tingkat" name="Tingkat" required>
                    <label for="BesarBayaran"><b>Besar Bayaran</b></label>
                    <input type="number" placeholder="Masukkan Besar Bayaran" name="BesarBayaran" id="BesarBayaran" required>
                    <div class="middle">
                        <button class="button" id="tombolAksi" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once "footer.php"; ?>
</body>
<script src="script.js"></script>
<script src="navbar.js"></script>

</html>