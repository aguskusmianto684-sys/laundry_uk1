<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';
session_start();

if (isset($_POST['tombol'])) {
    $id         = (int) $_POST['id'];
    $outlet_id  = (int) ($_POST['outlet_id'] ?? 0);
    $jenis      = escapeString($_POST['jenis']);
    $nama_paket = escapeString($_POST['nama_paket']);
    $harga      = (int) $_POST['harga'];

    $qUpdate = "UPDATE tb_paket 
                SET outlet_id = '$outlet_id',
                    jenis = '$jenis',
                    nama_paket = '$nama_paket',
                    harga = '$harga'
                WHERE id = '$id'";

    if (mysqli_query($connect, $qUpdate)) {
        echo "<script>
                alert('Data paket berhasil diupdate');
                window.location.href='../../pages/paket/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal update: " . mysqli_error($connect) . "');
                window.location.href='../../pages/paket/edit.php?id=$id';
              </script>";
    }
}
?>
