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

// Ambil data outlet
$qOutlet = "SELECT * FROM tb_outlet ORDER BY id DESC";
$result = mysqli_query($connect, $qOutlet) or die(mysqli_error($connect));
?>
<style>
/* Garis tabel lebih tipis dan halus */
.table,
.table-bordered,
.table-bordered th,
.table-bordered td {
  border: 1px solid #999 !important; /* abu-abu lebih soft */
  color: #000;
}

.table thead th {
  background-color: #f8f9fa; /* abu-abu muda bawaan bootstrap */
  border: 1px solid #999 !important;
}

.table tbody td {
  border: 1px solid #999 !important;
}

table {
  border-collapse: collapse !important;
}
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
                        <div class="card-header d-flex align-items-center justify-content-between"
                             style="background: linear-gradient(135deg, white, #0077b6, #90e0ef); border: none;">
                            <h5 class="mb-0">Tabel Outlet</h5>
                            <a href="create.php" class="btn btn-primary">Tambah</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="outletTable" class="table table-bordered table-hover table-striped align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Outlet</th>
                                            <th class="text-center">Alamat</th>
                                            <th class="text-center">Telepon</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        if ($result && $result->num_rows > 0):
                                            while ($item = $result->fetch_object()):
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $no ?></td>
                                                <td class="text-capitalize"><?= $item->nama ?></td>
                                                <td><?= $item->alamat ?></td>
                                                <td><?= $item->tlp ?></td>
                                                <td class="text-center">
                                                    <!-- <a href="./detail.php?id=<?= $item->id ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a> -->
                                                    <a href="./edit.php?id=<?= $item->id ?>" class="btn btn-warning btn-sm text-white">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../../actions/outlet/destroy.php?id=<?= $item->id ?>"
                                                       class="btn btn-danger btn-sm"
                                                       onclick="return confirm('Apakah anda yakin ingin menghapus outlet ini?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                                $no++;
                                            endwhile;
                                        else:
                                        ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Belum ada data Outlet</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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

<!-- DataTables Init -->

