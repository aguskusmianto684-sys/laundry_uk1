<?php
include __DIR__ . '/../../../config/connection.php';
include __DIR__ . '/../../../config/logActivity.php'; // ✅ pastikan fungsi logActivity tersedia
session_start();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $qDelete = "DELETE FROM tb_paket WHERE id='$id'";

    if (mysqli_query($connect, $qDelete)) {
        // ✅ Catat log aktivitas
        $user_id = $_SESSION['user_id'] ?? 0;
        logActivity($connect, $user_id, 'Hapus Paket', "ID Paket: $id");

        echo "<script>
                alert('Data paket berhasil dihapus');
                window.location.href='../../pages/paket/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal dihapus: " . mysqli_error($connect) . "');
                window.location.href='../../pages/paket/index.php';
              </script>";
    }
}
?>
