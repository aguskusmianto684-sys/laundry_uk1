<?php
session_start();
include __DIR__ . '/../../../config/connection.php';
include __DIR__ . '/../../../config/escapeString.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = escapeString($_POST['nama']);
    $username = escapeString($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = escapeString($_POST['role']);

    // Cek apakah username sudah ada
    $cek = mysqli_query($connect, "SELECT * FROM tb_user WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Username sudah terdaftar!');
            window.location.href='../../pages/user/register.php';
        </script>";
        exit();
    }

    $insert = "INSERT INTO tb_user (nama, username, password, role, outlet_id) 
               VALUES ('$nama', '$username', '$password', '$role', 1)"; // default outlet_id = 1
    if (mysqli_query($connect, $insert)) {
        echo "<script>
            alert('Registrasi berhasil, silakan login!');
            window.location.href='../../pages/user/login.php';
        </script>";
    } else {
        echo "<script>
            alert('Registrasi gagal!');
            window.location.href='../../pages/user/register.php';
        </script>";
    }
}
