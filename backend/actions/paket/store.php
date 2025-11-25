<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';
session_start();

if (isset($_POST['tombol'])) {
    $outlet_id  = (int) ($_POST['outlet_id'] ?? 0);
    $jenis      = escapeString($_POST['jenis']);
    $nama_paket = escapeString($_POST['nama_paket']);
    $harga      = (int) $_POST['harga'];

    $qInsert = "INSERT INTO tb_paket (outlet_id, jenis, nama_paket, harga) 
                VALUES ('$outlet_id', '$jenis', '$nama_paket', '$harga')";

    if (mysqli_query($connect, $qInsert)) {
        echo "<script>
                alert('Data paket berhasil ditambahkan');
                window.location.href='../../pages/paket/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan: " . mysqli_error($connect) . "');
                window.location.href='../../pages/paket/create.php';
              </script>";
    }
}
?>
