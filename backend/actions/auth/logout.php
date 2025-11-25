<?php
session_start();
include __DIR__ . '/../../../config/connection.php';
include __DIR__ . '/../../../config/logActivity.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    logActivity($connect, $user_id, "Logout");
}

session_unset();
session_destroy();

echo "<script>
    alert('Anda berhasil logout');
    window.location.href='../../pages/user/login.php';
</script>";
exit();
?>
