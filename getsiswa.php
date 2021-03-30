<?php
require_once "app.php";
if ($selectedUrl == "getsiswa.php") {
  //Don't come here
  // header("location:index.php");
}
if (!empty($_GET['nis'])) {
  $nis = $_GET['nis'];
  $stmt = $conn->prepare("SELECT * FROM tbsiswa WHERE NIS = ?");
  $stmt -> bind_param('s',$nis);
  $stmt -> execute();
  $result = $stmt -> get_result();
  $data = $result ->fetch_assoc();
  echo json_encode($data);
  /*
    ?>
    <script>
      document.getElementById("nis").value = <?= $data['NIS'] ?>
      document.getElementById("nama").value = <?= $data['NamaSiswa'] ?>
      document.getElementById("alamat").value = <?= $data['Alamat'] ?>

      document.getElementById("telp").value = <?= $data['NoTelp'] ?>
      document.getElementById("kelas").selectedIndex = <?= $data['Kodekelas'] ?>
    </script>
    <?php
  */

} elseif (!empty($_GET['kelas'])) {
  $stmt = $conn->prepare("SELECT * FROM tbsiswa WHERE NIS = ?");
}

?>