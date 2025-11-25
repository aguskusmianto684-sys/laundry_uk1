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

// update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($connect, $_POST['nama']);
    $alamat = mysqli_real_escape_string($connect, $_POST['alamat']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tlp = mysqli_real_escape_string($connect, $_POST['tlp']);

    $qUpdate = "UPDATE tb_pelanggan 
                SET nama='$nama', alamat='$alamat', jenis_kelamin='$jenis_kelamin', tlp='$tlp'
                WHERE id='$id'";
    mysqli_query($connect, $qUpdate) or die(mysqli_error($connect));

    echo "<script>alert('Pelanggan berhasil diperbarui!');window.location.href='index.php';</script>";
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
                    <h5 class="mb-0">Edit Pelanggan</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" value="<?= htmlspecialchars($data->nama) ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control"><?= htmlspecialchars($data->alamat) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="L" <?= $data->jenis_kelamin=='L'?'selected':'' ?>>Laki-laki</option>
                                <option value="P" <?= $data->jenis_kelamin=='P'?'selected':'' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>No. Telepon</label>
                            <input type="text" name="tlp" value="<?= htmlspecialchars($data->tlp) ?>" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <?php include '../../partials/footer.php'; ?>
</div>

<?php include '../../partials/script.php'; ?>
