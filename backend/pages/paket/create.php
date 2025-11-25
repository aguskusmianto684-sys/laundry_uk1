<?php
$page = "paket";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include __DIR__ . '/../../../config/logActivity.php';

// Ambil semua outlet untuk dropdown
$qOutlet = "SELECT * FROM tb_outlet ORDER BY nama ASC";
$outlets = mysqli_query($connect, $qOutlet) or die(mysqli_error($connect));
?>

<style>
    .card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2); /* bayangan lebih tegas */
}
</style>

<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?>

    <div class="content" style="padding-top: 90px;">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        <div class="card-header"
                            style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                            <h5 class="mb-0">Tambah Data Paket</h5>
                        </div>
                        <div class="card-body">
                            <form action="../../actions/paket/store.php" method="POST">
                                <div class="mb-3">
                                    <label>Outlet</label>
                                    <select name="outlet_id" class="form-control" required>
                                        <option value="">-- Pilih Outlet --</option>
                                        <?php while ($out = mysqli_fetch_assoc($outlets)): ?>
                                            <option value="<?= $out['id'] ?>"><?= htmlspecialchars($out['nama']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Jenis</label>
                                    <select name="jenis" class="form-control" required>
                                        <option value="kiloan">Kiloan</option>
                                        <option value="selimut">Selimut</option>
                                        <option value="bed_cover">Bed Cover</option>
                                        <option value="kaos">Kaos</option>
                                        <option value="lain">Lain</option>
                                    </select>

                                </div>
                                <div class="mb-3">
                                    <label>Nama Paket</label>
                                    <input type="text" name="nama_paket" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Harga</label>
                                    <input type="number" name="harga" class="form-control" required>
                                </div>
                                <button type="submit" name="tombol" class="btn btn-success">Simpan</button>
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