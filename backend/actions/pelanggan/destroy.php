<?php
include __DIR__ . '/../../../config/connection.php';
session_start();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $qDelete = "DELETE FROM tb_pelanggan WHERE id='$id'";

    if (mysqli_query($connect, $qDelete)) {
        // Catat log aktivitas
        $user_id = $_SESSION['user_id'] ?? 0;
        $ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $agent   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $qDelete = "DELETE FROM tb_transaksi WHERE id_transaksi='$id'";


        // $qLog = "INSERT INTO user_pelanggan 
        //             (user_id, activity, table_name, record_id, description, ip_address, user_agent, created_at) 
        //          VALUES 
        //             ('$user_id', 'delete', 'tb_member', '$id', 
        //             '" . mysqli_real_escape_string($connect, $desc) . "', 
        //             '$ip', '$agent', NOW())";
        // mysqli_query($connect, $qLog);

        echo "<script>
                alert('Data Pelanggan berhasil dihapus');
                window.location.href='../../pages/pelanggan/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal dihapus: " . mysqli_error($connect) . "');
                window.location.href='../../pages/pelanggan/index.php';
              </script>";
    }
}
?>
