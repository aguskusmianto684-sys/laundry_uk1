<?php
$page = "pengguna";
include __DIR__ . '/../../../config/connection.php';
include '../../../config/escapeString.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';

// ✅ Ambil ID dari parameter URL
$id = (int) $_GET['id'];

// ✅ Ambil data lama
$quser = "SELECT * FROM tb_user WHERE id='$id'";
$result = mysqli_query($connect, $quser) or die(mysqli_error($connect));
// ✅ Ambil semua outlet
$qoutlet = "SELECT * FROM tb_outlet ORDER BY nama ASC";
$resultOutlet = mysqli_query($connect, $qoutlet) or die(mysqli_error($connect));

if (mysqli_num_rows($result) == 0) {
    echo "<script>
        alert('Data tidak ditemukan!');
        window.location.href='./index.php';
    </script>";
    exit();
}
$user = mysqli_fetch_assoc($result);

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
                            <h5>Edit Data user</h5>
                        </div>
                        <div class="card-body">
                            <form action="../../actions/pengguna/update.php" method="POST">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="<?= htmlspecialchars($user['nama']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control"
                                        value="<?= htmlspecialchars($user['username']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label>Password (kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                                        <option value="kasir" <?= ($user['role'] == 'kasir') ? 'selected' : '' ?>>Kasir</option>
                                        <option value="owner" <?= ($user['role'] == 'owner') ? 'selected' : '' ?>>Owner</option>
                                    </select>
                                </div>

                                <div class="mb-3">
    <label>Outlet</label>
    <select name="outlet_id" class="form-control" required>
        <option value="">-- Pilih Outlet --</option>
        <?php while ($row = mysqli_fetch_assoc($resultOutlet)) { ?>
            <option value="<?= $row['id'] ?>" 
                <?= ($row['id'] == $user['outlet_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['nama']) ?>
            </option>
        <?php } ?>
    </select>
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