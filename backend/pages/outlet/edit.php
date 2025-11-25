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

// Ambil ID outlet dari URL
$id = (int) ($_GET['id'] ?? 0);

// Ambil data outlet dari DB
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">

                        <div class="card-header" 
                             style="background: linear-gradient(135deg, white, #0077b6, #90e0ef); border: none;">
                            <h5 class="mb-0">Edit Data Outlet</h5>
                        </div>

                        <div class="card-body">
                            <form action="../../actions/outlet/update.php" method="POST">
                                <input type="hidden" name="id" value="<?= $outlet['id'] ?>">

                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Outlet</label>
                                    <input type="text" id="nama" name="nama" class="form-control" 
                                           value="<?= htmlspecialchars($outlet['nama']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea id="alamat" name="alamat" class="form-control" required><?= htmlspecialchars($outlet['alamat']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="tlp" class="form-label">No. Telepon</label>
                                    <input type="text" id="tlp" name="tlp" class="form-control" 
                                           value="<?= htmlspecialchars($outlet['tlp']) ?>" required>
                                </div>

                                <button type="submit" name="tombol" class="btn btn-warning">Update</button>
                                <a href="./index.php" class="btn btn-secondary">Kembali</a>
                            </form>
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
