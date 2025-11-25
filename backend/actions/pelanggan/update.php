<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>alert('Silakan login dahulu!');window.location.href='../../pages/user/login.php';</script>";
    exit();
}

include __DIR__ . '/../../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $nama = mysqli_real_escape_string($connect, $_POST['nama']);
    $alamat = mysqli_real_escape_string($connect, $_POST['alamat']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tlp = mysqli_real_escape_string($connect, $_POST['tlp']);

    $q = "UPDATE tb_pelanggan 
          SET nama='$nama', alamat='$alamat', jenis_kelamin='$jenis_kelamin', tlp='$tlp'
          WHERE id='$id'";
    mysqli_query($connect, $q) or die(mysqli_error($connect));

    echo "<script>alert('Pelanggan berhasil diperbarui!');window.location.href='../../pages/pelanggan/index.php';</script>";
    exit();
}
?>
