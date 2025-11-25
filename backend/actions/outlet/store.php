<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';
session_start();

if (isset($_POST['tombol'])) {
    $nama   = escapeString($_POST['nama']);
    $alamat = escapeString($_POST['alamat']);
    $tlp    = escapeString($_POST['tlp']);

    $qInsert = "INSERT INTO tb_outlet (nama, alamat, tlp) 
                VALUES ('$nama', '$alamat', '$tlp)";

    if (mysqli_query($connect, $qInsert)) {
        echo "<script>
                alert('Data outlet berhasil ditambahkan');
                window.location.href='../../pages/outlet/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data: " . mysqli_error($connect) . "');
                window.location.href='../../pages/outlet/create.php';
              </script>";
    }
} else {
    header("Location: ../../pages/outlet/index.php");
    exit();
}
?>
