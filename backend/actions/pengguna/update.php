<?php
include '../../../config/connection.php';
include '../../../config/escapeString.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tombol'])) {
    $id        = (int) $_POST['id'];
    $nama      = escapeString($_POST['nama']);
    $username  = escapeString($_POST['username']);
    $role      = escapeString($_POST['role']);
    $outlet_id = (int) $_POST['outlet_id'];
    $password  = trim($_POST['password']);

    // ✅ Cek apakah username sudah dipakai user lain
    $qCheck = "SELECT id FROM tb_user WHERE username='$username' AND id != '$id' LIMIT 1";
    $checkResult = mysqli_query($connect, $qCheck);
    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>
            alert('Username sudah dipakai oleh user lain!');
            window.history.back();
        </script>";
        exit();
    }

    if (!empty($password)) {
        // kalau password diisi, update dengan password baru (hashing biar aman)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $qUpdate = "UPDATE tb_user 
                    SET nama='$nama', username='$username', password='$passwordHash',
                        role='$role', outlet_id='$outlet_id'
                    WHERE id='$id' LIMIT 1";
    } else {
        // kalau password kosong, tidak diupdate
        $qUpdate = "UPDATE tb_user 
                    SET nama='$nama', username='$username',
                        role='$role', outlet_id='$outlet_id'
                    WHERE id='$id' LIMIT 1";
    }

    if (mysqli_query($connect, $qUpdate)) {
        echo "<script>
            alert('Data pengguna berhasil diperbarui!');
            window.location.href='../../pages/pengguna/index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui data: " . mysqli_error($connect) . "');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('Akses tidak valid!');
        window.location.href='../../pages/pengguna/index.php';
    </script>";
}
?>
