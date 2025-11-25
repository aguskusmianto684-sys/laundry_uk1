<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}

$page = "pelanggan"; // halaman aktif
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';

// ambil semua data pelanggan
$q = "SELECT * FROM tb_pelanggan ORDER BY id DESC";
$result = mysqli_query($connect, $q) or die(mysqli_error($connect));
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

<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?>
    <div class="content" style="padding-top:90px;">
        <div class="page-inner">

            <div class="card shadow-lg">
                <div class="card-header d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                    <h5 class="mb-0">Tabel Pelanggan</h5>
                    <a href="create.php" class="btn btn-primary">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="pelangganTable" class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Jenis Kelamin</th>
                                    <th class="text-center">Telepon</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php $no = 1;
                                    while ($row = $result->fetch_object()): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="text-capitalize"><?= htmlspecialchars($row->nama) ?></td>
                                            <td><?= htmlspecialchars($row->alamat) ?></td>
                                            <td><?= $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                            <td><?= htmlspecialchars($row->tlp) ?></td>
                                            <td class="text-center">
                                                
                                                <a href="edit.php?id=<?= $row->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                <a href="../../actions/pelanggan/destroy.php?id=<?= $row->id ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                                   <i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-muted">Belum ada pelanggan</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include '../../partials/footer.php'; ?>
</div>

<?php include '../../partials/script.php'; ?>

<script>
    $(document).ready(function() {
        $('#pelangganTable').DataTable();
    });
</script>
