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

// Ambil ID paket dari URL
$id = (int) ($_GET['id'] ?? 0);

// Ambil data paket
$q = "SELECT * FROM tb_paket WHERE id = '$id' LIMIT 1";
$res = mysqli_query($connect, $q) or die(mysqli_error($connect));
$paket = mysqli_fetch_assoc($res);

if (!$paket) {
    echo "<script>
            alert('Data paket tidak ditemukan!');
            window.location.href='index.php';
          </script>";
    exit();
}

// Ambil daftar outlet untuk dropdown
$qOutlet = "SELECT * FROM tb_outlet ORDER BY nama ASC";
$outlets = mysqli_query($connect, $qOutlet) or die(mysqli_error($connect));
?>

<style>
    .card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2); /* bayangan lebih tegas */
}
</style>

<!-- Main Panel -->
<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?>

    <!-- Content -->
    <div class="content" style="padding-top: 90px;">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">

                        <div class="card-header" 
                             style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                            <h5 class="mb-0">Edit Data Paket</h5>
                        </div>

                        <div class="card-body">
                            <form action="../../actions/paket/update.php" method="POST">
                                <input type="hidden" name="id" value="<?= $paket['id'] ?>">

                                <div class="mb-3">
                                    <label for="outlet" class="form-label">Outlet</label>
                                    <select name="outlet_id" id="outlet" class="form-control" required>
                                        <?php while ($out = mysqli_fetch_assoc($outlets)): ?>
                                            <option value="<?= $out['id'] ?>" 
                                                <?= ($out['id'] == $paket['outlet_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($out['nama']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="jenis" class="form-label">Jenis</label>
                                    <select id="jenis" name="jenis" class="form-control" required>
                                        <option value="kiloan"     <?= ($paket['jenis'] == 'kiloan') ? 'selected' : '' ?>>Kiloan</option>
                                        <option value="selimut"    <?= ($paket['jenis'] == 'selimut') ? 'selected' : '' ?>>Selimut</option>
                                        <option value="bed_cover"  <?= ($paket['jenis'] == 'bed_cover') ? 'selected' : '' ?>>Bed Cover</option>
                                        <option value="kaos"       <?= ($paket['jenis'] == 'kaos') ? 'selected' : '' ?>>Kaos</option>
                                        <option value="lain"       <?= ($paket['jenis'] == 'lain') ? 'selected' : '' ?>>Lain</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="nama_paket" class="form-label">Nama Paket</label>
                                    <input type="text" id="nama_paket" name="nama_paket" class="form-control" 
                                           value="<?= htmlspecialchars($paket['nama_paket']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" id="harga" name="harga" class="form-control" 
                                           value="<?= htmlspecialchars($paket['harga']) ?>" required>
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

    <?php include '../../partials/footer.php'; ?>
</div>

<?php include '../../partials/script.php'; ?>
