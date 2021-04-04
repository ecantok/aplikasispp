<?php
require_once "app.php";
if (!empty($_GET['kodekelas'])) {
    $KodeKelas = $_GET['kodekelas'];
    $stmt = $conn->prepare("SELECT * FROM tbkelas WHERE KodeKelas = ?");
    $stmt->bind_param('s', $KodeKelas);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    echo json_encode($data);
} elseif (!empty($_GET['kelas'])) {
    $stmt = $conn->prepare("SELECT * FROM tbsiswa WHERE NIS = ?");
}
