<?php
include __DIR__ . '/../../../config/connection.php';
session_start();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Hapus dulu detail transaksi (anak)
    $qDetail = "DELETE FROM tb_detail_transaksi WHERE transaksi_id='$id'";
    mysqli_query($connect, $qDetail);

    // Baru hapus transaksi (parent)
    $qTransaksi = "DELETE FROM tb_transaksi WHERE id='$id'";
    if (mysqli_query($connect, $qTransaksi)) {

        // Catat log aktivitas (optional)
        $user_id = $_SESSION['user_id'] ?? 0;
        $ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $agent   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        // contoh deskripsi log
        $desc = "Menghapus transaksi ID $id";

$qLog = "INSERT INTO user_logs 
            (user_id, aktivitas, keterangan, ip_address, user_agent, created_at) 
         VALUES 
            ('$user_id', 'delete transaksi', '" . mysqli_real_escape_string($connect, $desc) . "', 
            '$ip', '$agent', NOW())";
mysqli_query($connect, $qLog);



        echo "<script>
                alert('Data transaksi berhasil dihapus');
                window.location.href='../../pages/transaksi/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal dihapus: " . mysqli_error($connect) . "');
                window.location.href='../../pages/transaksi/index.php';
              </script>";
    }
}
?>
