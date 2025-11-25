<?php
include '../../../config/connection.php';

$id = (int) $_GET['id'];

// cek apakah member masih dipakai di transaksi
$qCheck = "SELECT COUNT(*) as total FROM tb_transaksi WHERE member_id = $id";
$result = mysqli_query($connect, $qCheck);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    echo "<script>
        alert('Member tidak bisa dihapus karena masih dipakai di transaksi!');
        window.location.href='../../pages/member/index.php';
    </script>";
    exit();
}

// hapus kalau aman
$qDelete = "DELETE FROM tb_member WHERE id = $id LIMIT 1";
if (mysqli_query($connect, $qDelete)) {
    echo "<script>
        alert('Data member berhasil dihapus!');
        window.location.href='../../pages/member/index.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal hapus member: " . mysqli_error($connect) . "');
        window.history.back();
    </script>";
}
