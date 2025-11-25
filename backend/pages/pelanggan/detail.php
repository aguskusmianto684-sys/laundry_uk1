<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}

$page = "pelanggan";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';

$id = $_GET['id'] ?? 0;
$q = "SELECT * FROM tb_pelanggan WHERE id='$id' LIMIT 1";
$res = mysqli_query($connect, $q);
$data = mysqli_fetch_object($res);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan');window.location.href='index.php';</script>";
    exit();
}
?>

<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?>
    <div class="content" style="padding-top:90px;">
        <div class="page-inner">

            <div class="card shadow-lg mx-auto" style="max-width:600px;">
                <div class="card-header"
                     style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                    <h5 class="mb-0">Detail Pelanggan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th>Nama</th><td><?= htmlspecialchars($data->nama) ?></td></tr>
                        <tr><th>Alamat</th><td><?= htmlspecialchars($data->alamat) ?></td></tr>
                        <tr><th>Jenis Kelamin</th><td><?= $data->jenis_kelamin=='L'?'Laki-laki':'Perempuan' ?></td></tr>
                        <tr><th>Telepon</th><td><?= htmlspecialchars($data->tlp) ?></td></tr>
                    </table>
                    <a href="index.php" class="btn btn-secondary">⬅️ Kembali</a>
                </div>
            </div>

        </div>
    </div>
    <?php include '../../partials/footer.php'; ?>
</div>

<?php include '../../partials/script.php'; ?>
