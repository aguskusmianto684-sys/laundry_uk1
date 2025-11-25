<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';
session_start();

// Cek jika tombol update ditekan
if (isset($_POST['tombol'])) {
    $id     = (int) $_POST['id'];
    $nama   = escapeString($_POST['nama']);
    $alamat = escapeString($_POST['alamat']);
    $jk     = escapeString($_POST['jenis_kelamin']);
    $tlp    = escapeString($_POST['tlp']);

    $qUpdate = "UPDATE tb_member 
                SET nama='$nama', alamat='$alamat', jenis_kelamin='$jk', tlp='$tlp' 
                WHERE id='$id'";

    if (mysqli_query($connect, $qUpdate)) {
        // Catat log aktivitas
        $user_id = $_SESSION['user_id'] ?? 0;
        $ip      = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $agent   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $desc = "Update member ID $id → Nama: $nama, JK: $jk, Telp: $tlp";

        // $qLog = "INSERT INTO user_activities 
        //             (user_id, activity, table_name, record_id, description, ip_address, user_agent, created_at) 
        //          VALUES 
        //             ('$user_id', 'update', 'tb_member', '$id', 
        //             '" . mysqli_real_escape_string($connect, $desc) . "', 
        //             '$ip', '$agent', NOW())";
        // mysqli_query($connect, $qLog);

        echo "<script>
                alert('Data member berhasil diupdate');
                window.location.href='../../pages/member/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diupdate: " . mysqli_error($connect) . "');
                window.location.href='../../pages/member/edit.php?id=$id';
              </script>";
    }
}
?>
