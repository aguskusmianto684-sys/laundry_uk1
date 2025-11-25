<?php
$page = "member";
include __DIR__ . '/../../../config/connection.php';
include '../../../config/escapeString.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';

// ✅ Ambil ID dari parameter URL
$id = (int) $_GET['id'];

// ✅ Ambil data lama
$qMember = "SELECT * FROM tb_member WHERE id='$id'";
$result = mysqli_query($connect, $qMember) or die(mysqli_error($connect));
if (mysqli_num_rows($result) == 0) {
    echo "<script>
        alert('Data tidak ditemukan!');
        window.location.href='./index.php';
    </script>";
    exit();
}
$member = mysqli_fetch_assoc($result);
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
            <!-- contoh isi card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">

                        <div class="card-header" style="background: linear-gradient(135deg, white, #0077b6, #90e0ef); border: none;">
                            <h5>Edit Data Member</h5>
                        </div>
                        <div class="card-body">
                            <form action="../../actions/member/update.php" method="POST">
                                <input type="hidden" name="id" value="<?= $member['id'] ?>">

                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" 
                                           value="<?= htmlspecialchars($member['nama']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" required><?= htmlspecialchars($member['alamat']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control" required>
                                        <option value="L" <?= ($member['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="P" <?= ($member['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>No. Telepon</label>
                                    <input type="text" name="tlp" class="form-control" 
                                           value="<?= htmlspecialchars($member['tlp']) ?>" required>
                                </div>

                                <button type="submit" name="tombol" class="btn btn-success">Update</button>
                                <a href="./index.php" class="btn btn-primary">Kembali</a>
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
