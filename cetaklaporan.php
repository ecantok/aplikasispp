<?php
require_once 'App.php';
if (!$session) {
    header("Location:login.php");
}
switch ($_GET['cetak']) {
    case 'spp':
        $title = "Spp";
        $result = $conn->query("SELECT * FROM tbspp ORDER BY TahunAjaran ASC");
        $thead = "<thead>
      <th>No.</th>
      <th>Kode SPP</th>
      <th>Tahun Ajaran</th>
      <th>Tingkat</th>
      <th>Besar Bayaran</th>
    </thead>";
        $judulLaporan = "Laporan SPP";
        break;
    case 'kelas':
        $title = "Kelas";
        $result = $conn->query("SELECT tbkelas.*, tbspp.TahunAjaran, tbspp.Tingkat FROM tbkelas JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP ORDER BY TahunAjaran ASC");
        $thead = "<thead>
      <th style='width: 20px;'>No.</th>
      <th>Tahun Ajaran</th>
      <th>Tingkat</th>
      <th>Jurusan</th>
      <th>Nama Kelas</th>
    </thead>";
        $judulLaporan = "Laporan Data Kelas";
        break;
    case 'petugas':
        $title = "Petugas";
        $result = $conn->query("SELECT tbpetugas.*, tblogin.Username FROM `tbpetugas`
      LEFT JOIN tblogin ON tblogin.kode_petugas = tbpetugas.KodePetugas");
        $thead = "<thead>
      <th style='width: 20px;'>No.</th>
      <th>Nama Petugas</th>
      <th>Username</th>
      <th style='width:250px;'>Alamat</th>
      <th>Telp</th>
      <th>Jabatan</th>
    </thead>";
        $judulLaporan = "Laporan Petugas";
        break;
    case 'pembayaran':
        $title = "Pembayaran";
        $thead = "<thead>
        <th style='width: 20px;'>No.</th>
        <th>Kode Pembayaran</th>
        <th>Bulan Dibayar</th>
        <th>Tahun Dibayar</th>
        <th>Status</th>
        <th>Tunggakkan</th>
      </thead>";
        $judulLaporan = "Laporan Data SPP";
        break;
    default:
        header("Location:laporan.php");
        break;
}
$dataSekolah = $conn->query("SELECT * FROM tbdatasekolah");
?>
<html lang="en">

<head>
    <style>
        * {
            font-family: 'Times New Roman', Times, serif;
        }

        .table-detail {
            border: solid 1px grey;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 10px auto 10px auto;
        }

        .table-detail thead th {
            border: solid 1px grey;
            padding: 10px;
            text-decoration: none;
            text-align: center;
        }

        .table-detail tbody td {
            border: solid 1px grey;
            color: #333;
            padding: 10px;
        }

        .header-nama {
            text-align: center;
            text-transform: capitalize;
            padding-top: 0px;
            padding-bottom: 0px;
            font-size: 22pt;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td style="text-align: center;" rowspan="3" width="150px"><img width="150px" height="150px" src="<?= BASEURL ?>/img/<?= $dataSekolah['gambar_logo'] ?>" alt="Gambar logo" srcset=""></td>
            <td class="header-nama" colspan="2">
                <p style="margin-bottom: 0xp; margin-top: 0px;"><?= $dataSekolah['nama_sekolah'] ?></p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;"><?= $dataSekolah['alamat'] . ', ' . $dataSekolah['kelurahan'] . ', ' . $dataSekolah['kota'] ?></td>
        </tr>
        <tr>
            <td style="text-align: center;">Tel/Fax: <?= $dataSekolah['no_telp'] ?>, email: <?= $dataSekolah['email'] ?></td>
        </tr>
    </table>
    <hr style="border:2px solid black;">
    <hr style="border:1px solid black; margin-bottom: 100px;">
    <div style="text-align: center;"> <?= $judulLaporan ?></div>
    <?php
    if (!empty($_GET['kodespp']) && $_GET['kodespp'] != '') {
        $stmt = $conn->prepare("SELECT tbsiswa.NIS, tbsiswa.NamaSiswa, tbsppsiswa.kode_spp_siswa,  tbkelas.NamaKelas, tbspp.TahunAjaran, tbspp.BesarBayaran FROM tbsiswa 
      JOIN tbsppsiswa ON tbsiswa.NIS = tbsppsiswa.nis
      JOIN tbkelas ON tbsppsiswa.Kodekelas = tbkelas.KodeKelas 
      JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP 
      WHERE tbsppsiswa.kode_spp_siswa = :kode_spp_siswa");
        $stmt->bindParam(":kode_spp_siswa", $_GET['kodespp']);
        $stmt->execute();
        $dataStudent = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($resultStudent->num_rows != 0) {
    ?>
            <table>
                <tr>
                    <td style="width: 150px;">NIS</td>
                    <td>:</td>
                    <td><?= $dataStudent['NIS'] ?></td>
                </tr>
                <tr>
                    <td>Nama Siswa</td>
                    <td>:</td>
                    <td><?= $dataStudent['NamaSiswa'] ?></td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td><?= $dataStudent['NamaKelas'] ?></td>
                </tr>
                <tr>
                    <td>Tahun Ajaran</td>
                    <td>:</td>
                    <td><?= $dataStudent['TahunAjaran'] ?></td>
                </tr>
                <tr>
                    <td>Jumlah Bayaran</td>
                    <td>:</td>
                    <td><?= "Rp." . $app->numberformat($dataStudent['BesarBayaran']) ?></td>
                </tr>
            </table>
    <?php }
    }
    ?>
    <table class="table-detail" style="width: 100%; margin-top: 20px; border: 1;">
        <?= $thead ?>
        <tbody>

            <?php 
            if ($_GET['cetak'] == 'pembayaran' && $resultStudent->num_rows != 0) {
                $stmtSpp = $conn->prepare("SELECT tbpembayaran.*, (tbspp.BesarBayaran - SUM(tbtransaksi.jumlah_bayaran)) AS sisa FROM tbpembayaran
      LEFT JOIN tbtransaksi ON tbtransaksi.kodepembayaran = tbpembayaran.KodePembayaran
      JOIN tbsppsiswa ON tbsppsiswa.kode_spp_siswa = tbpembayaran.kode_spp_siswa
      JOIN tbkelas ON tbkelas.KodeKelas = tbsppsiswa.kodekelas
      JOIN tbspp ON tbspp.KodeSPP = tbkelas.KodeSPP
      WHERE tbsppsiswa.kode_spp_siswa = :kodespp 
      GROUP BY tbpembayaran.KodePembayaran ORDER BY `tbpembayaran`.`BulanDibayar` ASC");
                $stmtSpp->bindParam(":kodespp", $_GET['kodespp']);
                $stmtSpp->execute();
                $resultSpp = $stmtSpp->fetchAll(PDO::FETCH_ASSOC);
                $no = 1;
                $total = 0;
                foreach ($resultSpp as $dataSPP) {
                    $sisa = $dataSPP['sisa'];
                    if ($sisa === null) {
                        $sisa = $dataStudent['BesarBayaran'];
                        $statuspembayaran = "-";
                    } else if ($sisa >= 1) {
                        $statuspembayaran = "BELUM LUNAS";
                    } else if ($sisa <= 0) {
                        $statuspembayaran = "LUNAS";
                        $sisa = 0;
                    }
            ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $dataSPP['KodePembayaran'] ?></td>
                        <td><?= $dataSPP['BulanDibayar'] ?></td>
                        <td><?= $dataSPP['TahunDibayar'] ?></td>
                        <td style="text-align: center;"><?= $statuspembayaran ?></td>
                        <td style="text-align: end;"><?= ($sisa != 0) ? "Rp." . $app->numberformat($sisa) : "-"; ?></td>

                    </tr>
                <?php
                    $no++;
                    $total += $sisa;
                }
                ?>
                <tr>
                    <td colspan="5">Total Tunggakkan</td>
                    <td style="text-align: end;">Rp.<?= $app->numberformat($total) ?></td>
                </tr>
                <?php
            } elseif ($_GET['cetak'] == 'spp' && $result->num_rows != 0) {
                foreach ($result as $data) {
                ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $data['KodeSPP'] ?></td>
                        <td><?= $data['TahunAjaran'] ?></td>
                        <td><?= $data['Tingkat'] ?></td>
                        <td><?= $data['BesarBayaran'] ?></td>
                    </tr>
                <?php
                    $no++;
                }
            } elseif ($_GET['cetak'] == 'kelas' && $result->num_rows != 0) {
                foreach ($result as $data) {
                ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td align="center"><?= $data['TahunAjaran'] ?></td>
                        <td align="center"><?= $data['Tingkat'] ?></td>
                        <td align="center"><?= $data['Jurusan'] ?></td>
                        <td align="center"><?= $data['NamaKelas'] ?></td>
                    </tr>
                <?php
                    $no++;
                }
            } elseif ($_GET['cetak'] == 'petugas' && $result->num_rows != 0) {
                foreach ($result as $data) {
                ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $data['NamaPetugas'] ?></td>
                        <td><?= $data['Username'] ?></td>
                        <td><?= $data['Alamat'] ?></td>
                        <td><?= $data['Telp'] ?></td>
                        <td><?= $data['Jabatan'] ?></td>
                    </tr>
            <?php
                    $no++;
                }
            }


            ?>

        </tbody>
    </table>


    <script>
        //Untuk membuat halaman print
        window.print();
    </script>

</body>

</html>