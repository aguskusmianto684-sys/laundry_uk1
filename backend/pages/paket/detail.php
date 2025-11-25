<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}

$page = "paket";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';

// Ambil ID paket dari parameter
$id = (int) ($_GET['id'] ?? 0);

// Query data paket + join outlet
$q = "SELECT p.*, o.nama AS nama_outlet 
      FROM tb_paket p
      JOIN tb_outlet o ON p.outlet_id = o.id
      WHERE p.id = '$id' LIMIT 1";
$res = mysqli_query($connect, $q) or die(mysqli_error($connect));
$paket = mysqli_fetch_assoc($res);

if (!$paket) {
    echo "<script>
            alert('Data paket tidak ditemukan!');
            window.location.href='index.php';
          </script>";
    exit();
}
?>

<style>
    .card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2); /* bayangan lebih tegas */
}
</style>

<!-- Main Panel -->
<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?> <!-- navbar -->

    <!-- Content -->
    <div class="content" style="padding-top: 90px;">
        <div class="page-inner">

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header text-white" 
                             style="background: linear-gradient(135deg, #0077b6, #023e8a);">
                            <h5 class="mb-0">Detail Paket</h5>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">ID Paket</th>
                                    <td><?= htmlspecialchars($paket['id']) ?></td>
                                </tr>
                                <tr>
                                    <th>Outlet</th>
                                    <td><?= htmlspecialchars($paket['nama_outlet']) ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis</th>
                                    <td><?= ucfirst($paket['jenis']) ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Paket</th>
                                    <td><?= htmlspecialchars($paket['nama_paket']) ?></td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></td>
                                </tr>
                            </table>

                            <div class="mt-3">
                                <a href="index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- End Content -->

    <?php include '../../partials/footer.php'; ?> <!-- footer -->
</div>
<!-- End Main Panel -->

<?php include '../../partials/script.php'; ?> <!-- JS -->
