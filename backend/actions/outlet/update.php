<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';
session_start();

if (isset($_POST['tombol'])) {
    $id     = (int) $_POST['id'];
    $nama   = escapeString($_POST['nama']);
    $alamat = escapeString($_POST['alamat']);
    $tlp    = escapeString($_POST['tlp']);

    $qUpdate = "UPDATE tb_outlet 
                SET nama = '$nama', alamat = '$alamat', tlp = '$tlp'
                WHERE id = '$id'";

    if (mysqli_query($connect, $qUpdate)) {
        echo "<script>
                alert('Data outlet berhasil diupdate');
                window.location.href='../../pages/outlet/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal update: " . mysqli_error($connect) . "');
                window.location.href='../../pages/outlet/edit.php?id=$id';
              </script>";
    }
}
?>
