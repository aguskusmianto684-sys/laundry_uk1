<?php
session_start();
include __DIR__ . '/../../../config/connection.php';
include __DIR__ . '/../../../config/escapeString.php';
include __DIR__ . '/../../../config/logActivity.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = escapeString($_POST['username']);
    $password = $_POST['password'];

    // ✅ Prepared statement agar aman dari SQL Injection
    $stmt = $connect->prepare("SELECT * FROM tb_user WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['nama']      = $user['nama'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['role']      = strtolower($user['role']); 
            $_SESSION['outlet_id'] = $user['outlet_id'];

            // log aktivitas login
            logActivity($connect, $user['id'], 'Login', 'User berhasil login ke sistem');

            echo "<script>
                alert('Login berhasil! Selamat datang {$user['nama']}');
                window.location.href='../../pages/dashboard/index.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('Password salah!');
                window.location.href='../../pages/user/login.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Username tidak ditemukan!');
            window.location.href='../../pages/user/login.php';
        </script>";
    }
}
