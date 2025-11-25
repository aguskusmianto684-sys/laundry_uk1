<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}

$page = "outlet";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';

// Ambil ID dari parameter
$id = (int) ($_GET['id'] ?? 0);

// Query data outlet
$q = "SELECT * FROM tb_outlet WHERE id = '$id' LIMIT 1";
$res = mysqli_query($connect, $q) or die(mysqli_error($connect));
$outlet = mysqli_fetch_assoc($res);

if (!$outlet) {
    echo "<script>
            alert('Data outlet tidak ditemukan!');
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
                            <h5 class="mb-0">Detail Outlet</h5>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">ID Outlet</th>
                                    <td><?= htmlspecialchars($outlet['id']) ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Outlet</th>
                                    <td><?= htmlspecialchars($outlet['nama']) ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?= htmlspecialchars($outlet['alamat']) ?></td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td><?= htmlspecialchars($outlet['tlp']) ?></td>
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
