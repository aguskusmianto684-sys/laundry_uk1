<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}

$page = "aktivitas";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';

// ambil data log
$qLog = "
    SELECT l.id, u.nama AS user_nama, l.aktivitas, l.created_at
    FROM user_logs l
    LEFT JOIN tb_user u ON l.user_id = u.id
    ORDER BY l.created_at DESC
";
$result = mysqli_query($connect, $qLog) or die(mysqli_error($connect));
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
                    <h5 class="mb-0">Aktivitas User</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="aktivitasTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Aktivitas</th>
                                    <th class="text-center">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php $no=1; while ($row = $result->fetch_object()): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row->user_nama) ?></td>
                                            <td><?= htmlspecialchars($row->aktivitas) ?></td>
                                            <td><?= date('d-m-Y H:i:s', strtotime($row->created_at)) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-muted">Belum ada aktivitas</td>
                                    </tr>
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