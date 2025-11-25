<?php
session_start();
include '../../../config/connection.php';
include '../../../config/escapeString.php';

if (isset($_POST['tombol'])) {
    $nama      = escapeString($_POST['nama']);
    $username  = escapeString($_POST['username']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT); // simpan hash
    $outlet_id = (int) $_POST['outlet_id'];
    $role      = escapeString($_POST['role']);

    // ✅ Cek apakah username sudah ada
    $qCheck = "SELECT id FROM tb_user WHERE username = '$username' LIMIT 1";
    $checkResult = mysqli_query($connect, $qCheck);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>
                alert('Username sudah dipakai, silakan pilih username lain!');
                window.location.href='../../pages/pengguna/create.php';
              </script>";
        exit();
    }

    // ✅ Insert data baru
    $qInsert = "INSERT INTO tb_user (nama, username, password, outlet_id, role) 
                VALUES ('$nama', '$username', '$password', '$outlet_id', '$role')";

    if (mysqli_query($connect, $qInsert)) {
        echo "<script>
                alert('User baru berhasil ditambahkan');
                window.location.href='../../pages/pengguna/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan user: " . mysqli_error($connect) . "');
                window.location.href='../../pages/pengguna/create.php';
              </script>";
    }
}
?>
