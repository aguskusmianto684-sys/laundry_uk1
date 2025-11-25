<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';
include '../../../config/logActivity.php'; // pastikan logActivity() ada di sini
session_start();

// Cek jika tombol simpan ditekan
if (isset($_POST['tombol'])) {
    $nama   = escapeString($_POST['nama']);
    $alamat = escapeString($_POST['alamat']);
    $jk     = escapeString($_POST['jenis_kelamin']);
    $tlp    = escapeString($_POST['tlp']);

    $qInsert = "INSERT INTO tb_member (nama, alamat, jenis_kelamin, tlp) 
                VALUES ('$nama', '$alamat', '$jk', '$tlp')";

    if (mysqli_query($connect, $qInsert)) {
        $newId = mysqli_insert_id($connect);

        // ================= Log aktivitas =================
        $user_id = $_SESSION['user_id'] ?? 0;
        $keterangan = "Menambahkan member baru → Nama: $nama, JK: $jk, Telp: $tlp";
        logActivity($connect, $user_id, 'Tambah Member', $keterangan);
        // =================================================

        echo "<script>
                alert('Data member berhasil ditambahkan');
                window.location.href='../../pages/member/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan: " . mysqli_error($connect) . "');
                window.location.href='../../pages/member/create.php';
              </script>";
    }
}
?>
