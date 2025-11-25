<?php
$page = "pengguna";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';

// Ambil data user
$quser = "SELECT * FROM tb_user ORDER BY id DESC";
$result = mysqli_query($connect, $quser) or die(mysqli_error($connect));

// Ambil data outlet
$qoutlet = "SELECT * FROM tb_outlet ORDER BY nama ASC";
$resultOutlet = mysqli_query($connect, $qoutlet) or die(mysqli_error($connect));
?>


<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        /* bayangan lebih tegas */
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
                            <h5>Tambah Data Pengguna</h5>
                        </div>
                        <div class="card-body">
                            <form action="../../actions/pengguna/store.php" method="POST">
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Outlet</label>
                                    <select name="outlet_id" class="form-control" required>
                                        <option value="">-- Pilih Outlet --</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultOutlet)) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="admin">Admin</option>
                                        <option value="kasir">Kasir</option>
                                        <option value="owner">Owner</option>
                                    </select>
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


