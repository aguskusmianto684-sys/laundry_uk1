<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
            alert('Silakan login dahulu!');
            window.location.href='../../pages/user/login.php';
          </script>";
    exit();
}

// include koneksi & logActivity
include '../../../config/connection.php';
include '../../../config/logActivity.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // amankan input
    $nama          = mysqli_real_escape_string($connect, $_POST['nama'] ?? '');
    $alamat        = mysqli_real_escape_string($connect, $_POST['alamat'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tlp           = mysqli_real_escape_string($connect, $_POST['tlp'] ?? '');

    // query insert
    $q = "INSERT INTO tb_pelanggan (nama, alamat, jenis_kelamin, tlp) 
          VALUES ('$nama', '$alamat', '$jenis_kelamin', '$tlp')";

    if (mysqli_query($connect, $q)) {
        $newId = mysqli_insert_id($connect);

        // ========== Log aktivitas ==========
        $user_id    = $_SESSION['user_id'] ?? 0;
        $keterangan = "Menambahkan pelanggan baru → Nama: $nama, JK: $jenis_kelamin, Telp: $tlp";
        logActivity($connect, $user_id, 'Tambah Pelanggan', $keterangan);
        // ===================================

        echo "<script>
                alert('Pelanggan berhasil ditambahkan!');
                window.location.href='../../pages/pelanggan/index.php';
              </script>";
    } else {
        $error = addslashes(mysqli_error($connect));
        echo "<script>
                alert('Gagal menambahkan pelanggan: $error');
                window.location.href='../../pages/pelanggan/create.php';
              </script>";
    }
    exit();
}
?>
