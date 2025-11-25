<?php
function logActivity($connect, $user_id, $aktivitas, $keterangan = '') {
    $ip    = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    $aktivitas  = mysqli_real_escape_string($connect, $aktivitas);
    $keterangan = mysqli_real_escape_string($connect, $keterangan);

    $sql = "INSERT INTO user_logs (user_id, aktivitas, keterangan, ip_address, user_agent)
            VALUES ('$user_id', '$aktivitas', '$keterangan', '$ip', '$agent')";
    mysqli_query($connect, $sql) or die(mysqli_error($connect));
}
