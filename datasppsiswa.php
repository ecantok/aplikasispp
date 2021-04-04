<?php
require_once 'app.php';
require_once 'navbar.php';
//Cek hak akses, Defaultnya sudah ada admin
if (!$session) {
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
    <title>Data SPP Siswa || Pembayaran SPP</title>
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

        <h2>Data SPP Siswa</h2>
        <div>
            <?php
            $app->pesanDialog();
            ?>

        </div>
        <?php if (!$app->cekPemissionLevel($levelUser, "Siswa")) { ?>
            <?php if (empty($_GET['kodepembayaran'])) : ?>
                <form action="" method="get">
                    <div class="flex">
                        <div>
                            <label for="nis"><b>NIS</b></label>
                            <?php $nis = (!empty($_GET['nis']) && $_GET['nis'] != '') ? $_GET['nis'] : ''; ?>
                            <input type="text" name="nis" id="nis" value="<?= $nis ?>">
                        </div>
                        <input style="margin: 15px 10px;" type="submit" value="Cari">
                    </div>
                </form>
            <?php endif; ?>
            <p class="info-text"><i>NIS Dapat dicari <a href="pendataansiswa.php" class="link">di sini</a></i></p>
            <hr>
        <?php
        }
        if (!empty($_GET['nis']) && $_GET['nis'] != '' || ($app->cekPemissionLevel($levelUser, "Siswa"))) {
        ?>
            <br>
            <?php
            $query = "SELECT tbspp.TahunAjaran, tbsppsiswa.kode_spp_siswa FROM tbsiswa 
    JOIN tbsppsiswa ON tbsiswa.NIS = tbsppsiswa.nis
    JOIN tbkelas ON tbkelas.KodeKelas = tbsppsiswa.kodekelas
    JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP
    WHERE tbsppsiswa.nis = ?
    ";
            $stmtTahunAjaran = $conn->prepare($query);
            $nis = ($app->cekPemissionLevel($levelUser, "Siswa")) ? $idUser : $nis;
            $stmtTahunAjaran->bind_param("i", $nis);
            $stmtTahunAjaran->execute();
            $result = $stmtTahunAjaran->get_result();
            if ($result->num_rows > 0) {
                $result->fetch_all(MYSQLI_ASSOC);
            ?>
                <label for="tahunajaran">Pilih Tahun Ajaran</label>
                <select name='tahunajaran' id="tahunajaran" required>
                    <option value="">-Tahun Ajaran-</option>
                    <?php
                    foreach ($result as $data) {
                        if (isset($_GET['kodespp']) && !empty($_GET['kodespp']) && $_GET['kodespp'] == $data['kode_spp_siswa']) {
                            echo "
        <option value=" . $data['kode_spp_siswa'] . " selected>" . $data['TahunAjaran'] . "</option>
        ";
                        } else {
                            echo "
        <option value=" . $data['kode_spp_siswa'] . ">" . $data['TahunAjaran'] . "</option>
        ";
                        }
                    }
                    echo "</select></td>";
                } else {
                    ?>
                    <p>Data SPP tidak ada</p>
                <?php
                }
                ?>
                <hr>
                <?php } elseif (!empty($_GET['kodepembayaran'])) {
                $kodepembayaran = $_GET['kodepembayaran'];
                $stmtSiswa = $conn->prepare("SELECT tbsiswa.NIS, tbsiswa.NamaSiswa, tbsppsiswa.kode_spp_siswa,  tbkelas.NamaKelas, tbspp.TahunAjaran, tbspp.BesarBayaran, tbpembayaran.BulanDibayar,
      (tbspp.BesarBayaran - SUM(tbtransaksi.jumlah_bayaran)) AS sisa
      FROM tbpembayaran 
      LEFT JOIN tbtransaksi ON tbtransaksi.kodepembayaran = tbpembayaran.KodePembayaran
      JOIN tbsppsiswa ON tbsppsiswa.kode_spp_siswa = tbpembayaran.kode_spp_siswa
      JOIN tbsiswa ON tbsiswa.NIS = tbsppsiswa.nis
      JOIN tbkelas ON tbsppsiswa.Kodekelas = tbkelas.KodeKelas 
      JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP 
      WHERE tbpembayaran.KodePembayaran = ? GROUP BY tbpembayaran.KodePembayaran
      ");
                $stmtSiswa->bind_param("s", $kodepembayaran);
                $stmtSiswa->execute();
                $resultSiswa = $stmtSiswa->get_result();
                $dataSiswa = $resultSiswa->fetch_assoc();
                if ($resultSiswa->num_rows > 0) {
                ?>
                    <div style="overflow-x: auto;">
                        <fieldset class="fieldset">
                            <legend class="legend">
                                <h3>Biodata Siswa</h3>
                            </legend>
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
                                    <td><?= "Rp." . $app->numberformat($dataSiswa['BesarBayaran']) ?></td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                    <hr>
                    <?php
                    $sisa = $dataSiswa['sisa'];
                    $statuspembayaran = "BELUM DIBAYAR";
                    if ($sisa === null) {
                    } else if ($sisa >= 1) {
                        $statuspembayaran = "BELUM LUNAS";
                    } else if ($sisa <= 0) {
                        $statuspembayaran = "LUNAS";
                    }
                    ?>
                    <a href="datasppsiswa.php?nis=<?= $dataSiswa['NIS'] . '&q=' . $dataSiswa['kode_spp_siswa'] ?>" class="link">&#171; Kembali</a>
                    <h3>Detail Pembayaran SPP Siswa untuk bulan <?= $dataSiswa['BulanDibayar'] ?> (<?= $statuspembayaran ?>)</h3>
                    <?php if ($sisa === null || $sisa >= 1) { ?>
                        <div class="mb">
                            <a href="pembayaranspp.php?kodepembayaran=<?= $kodepembayaran ?>" class="button">Buat Transaksi</a>
                        </div>
                    <?php } ?>
                    <div style="overflow-x: auto;">
                        <table class="table-view" id="table">
                            <thead>
                                <th>No.</th>
                                <th>ID Transaksi</th>
                                <th>Nama Petugas</th>
                                <th>Tanggal Transaksi</th>
                                <th>Metode Transaksi</th>
                                <th>Jumlah Bayaran</th>
                                <th>Kembalian</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                $stmtSiswa->close();
                                $stmtTransaksi = $conn->prepare("SELECT tbtransaksi.*, tbpetugas.NamaPetugas FROM `tbtransaksi`
          LEFT JOIN tbpetugas ON tbpetugas.KodePetugas = tbtransaksi.kodepetugas
          WHERE tbtransaksi.kodepembayaran = ?");
                                $stmtTransaksi->bind_param("s", $kodepembayaran);
                                $stmtTransaksi->execute();
                                $resultTransaksi = $stmtTransaksi->get_result();
                                $fetchTransaksi = $resultTransaksi->fetch_all(MYSQLI_ASSOC);
                                $no = 1;
                                if ($resultTransaksi->num_rows >= 1) {
                                    foreach ($fetchTransaksi as $dataTransaksi) {
                                ?>
                                        <tr>
                                            <td><?= $no ?></td>
                                            <td><?= $dataTransaksi['idtransaksi'] ?></td>
                                            <td><?= $dataTransaksi['NamaPetugas'] ?></td>
                                            <td><?= $dataTransaksi['tgl_transaksi'] ?></td>
                                            <td><?= $dataTransaksi['metode_transaksi'] ?></td>
                                            <td><?= $dataTransaksi['jumlah_bayaran'] ?></td>
                                            <td><?= $dataTransaksi['kembalian'] ?></td>
                                            <td><?= $dataTransaksi['keterangan'] ?></td>
                                            <td>
                                                <?php if ($idUser == $dataTransaksi['kodepetugas'] || $app->cekPemissionLevel($levelUser)) { ?>
                                                    <span><a class="btn-small blue" href='pembayaranspp.php?edit=true&idtransaksi=<?= $dataTransaksi['idtransaksi'] ?>'">Edit</a></span>
                <span><a class=" btn-small red" onclick="deletepembayaran('<?= $dataTransaksi['idtransaksi'] ?>','<?= $kodepembayaran ?>')">Delete</a></span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php $no++;
                                    }
                                } else { ?>
                                    <td colspan="8"> Data Kosong</td>
                                <?php }
                                $stmtTransaksi->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>

            <?php }
            }  ?>
            <div id="respon"></div>
    </div>
    <?php require_once "footer.php"; ?>
    <span class="close"></span>
</body>
<script>
    function editpembayaran(idtransaksi) {
        if (idtransaksi == "") {
            document.getElementById("respon").innerHTML = "";
            return;
        }
        return location.href = "pembayaranspp.php?idtransaksi=" + idtransaksi + "&edit=true";
    }
    //DELETE Kelas 
    function deletepembayaran(idtransaksi, kodepembayaran) {
        if (confirm("Yakin ingin hapus data transaksi dengan ID Transaksi " + idtransaksi + "?")) {
            location.href = "deletetransaksi.php?id=" + idtransaksi + "&kodepembayaran=" + kodepembayaran;
        }
    }
    const selectTh = document.getElementById("tahunajaran");
    const address = "getdataspp.php";
    selectTh.addEventListener('change', (event) => {
        kodespp = event.target.value;
        if (kodespp == "") {
            document.getElementById("tabel").innerHTML = "Data belum dipilih";
            return;
        } else {
            ajax(address, "?q=" + kodespp);
        }
    });
    <?php if (!empty($_GET['q'])) {
        echo "
    const kodesppGet = '{$_GET['q']}';
    ajax(address, '?q='+kodesppGet);
    ";
    } ?>

    function ajax(addr, param) {
        if (window.XMLHttpRequest) {
            //Browser untuk IE7+, Firefox, Chrome, and Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            //Browser untuk IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 & this.status == 200) {
                document.getElementById("respon").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", addr + param, true);
        xmlhttp.send();
    }
</script>
<script src="navbar.js"></script>

</html>