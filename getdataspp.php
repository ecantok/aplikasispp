<?php
require_once "app.php";
if ($selectedUrl == "getsiswa.php") {
  //Don't come here
  header("location:index.php");
}
if ($_SERVER["REQUEST_METHOD"]=="GET") {
  if (isset($_GET['q'])) {
    $q = intval($_GET['q']);
      $stmtSiswa = $conn->prepare("SELECT tbsiswa.NIS, tbsiswa.NamaSiswa, tbsppsiswa.kode_spp_siswa,  tbkelas.NamaKelas, tbspp.TahunAjaran, tbspp.BesarBayaran FROM tbsiswa 
      JOIN tbsppsiswa ON tbsiswa.NIS = tbsppsiswa.nis
      JOIN tbkelas ON tbsppsiswa.Kodekelas = tbkelas.KodeKelas 
      JOIN tbspp ON tbkelas.KodeSPP = tbspp.KodeSPP 
      WHERE tbsppsiswa.kode_spp_siswa = ?");
      $stmtSiswa->bind_param("s", $q);
      $stmtSiswa->execute();
      $resultSiswa = $stmtSiswa->get_result();
      $dataSiswa = $resultSiswa->fetch_assoc();
      if ($resultSiswa->num_rows > 0) {
    ?>
    <h3>Biodata Siswa</h3>
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
    <hr>
    <h3>Tagihan Spp</h3>
    <div style="overflow-x: auto;">
      <table class="table-view">
        <thead>
          <th>No.</th>
          <th>Kode Pembayaran</th>
          <th>Petugas</th>
          <th>Tgl. Pembayaran</th>
          <th>Bulan Dibayar</th>
          <th>Tahun Dibayar</th>
          <th>Status</th>
          <th>Action</th>
        </thead>
        <tbody>
          
          <?php 
          $stmtSpp = $conn->prepare("SELECT tbpembayaran.*, tbpetugas.* FROM tbpembayaran JOIN tbpetugas ON tbpembayaran.KodePetugas = tbpetugas.KodePetugas WHERE kode_spp_siswa = ? ORDER BY `tbpembayaran`.`TahunDibayar` ASC");
          $stmtSpp->bind_param("s",$dataSiswa['kode_spp_siswa']);
          $stmtSpp->execute();
          $resultSPP = $stmtSpp->get_result();
          $no = 1;
          while ($dataSPP = $resultSPP->fetch_assoc()) {
            ?>
            <tr>
              <td><?=$no?></td>
              <td><?=$dataSPP['KodePembayaran']?></td>
              <td><?=$dataSPP['NamaPetugas']?></td>
              <td style="text-align: center;"><?= ($dataSPP['TglPembayaran']=='0000-00-00')? "-" : $dataSPP['TglPembayaran']?></td>
              <td><?=$dataSPP['BulanDibayar']?></td>
              <td><?=$dataSPP['TahunDibayar']?></td>
              <td style="text-align: center;"><?=$dataSPP['StatusPembayaran']?></td>
              <td style="text-align: center;"><?php
              if($dataSPP['StatusPembayaran'] =='-'){ echo
              "<a href='entripembayaran.php?act=bayar&id={$dataSPP['KodePembayaran']}'>Bayar</a>"; } else {
                  echo"<a style='color:red' href='entripembayaran.php?act=batal&id={$dataSPP['KodePembayaran']}'>Batal</a>" ;
              
              } 
              ?> </td>
              
            </tr>
            <?php
            $no++;
          }
          ?>
          
        </tbody>
      </table>
    </div>
    <?php } else {
      echo "<p>Data tidak ditemukan.</p> ";
    }
  }
}