<?php

$page = "member";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';

// Ambil data member
$qMember = "SELECT * FROM tb_member ORDER BY id DESC";
$result = mysqli_query($connect, $qMember) or die(mysqli_error($connect));
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
                                <h5>Tambah Data Member</h5>
                            </div>
                            <div class="card-body">
                                <form action="../../actions/member/store.php" method="POST">
                                    <div class="mb-3">
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-control" required>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>No. Telepon</label>
                                        <input type="text" name="tlp" class="form-control" required>
                                    </div>
                                    <button type="submit" name="tombol" class="btn btn-success">Simpan</button>
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