<style>
    /* Garis tabel lebih tipis dan halus */
    .table,
    .table-bordered,
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #999 !important;
        /* abu-abu lebih soft */
        color: #000;
    }

    .table thead th {
        background-color: #f8f9fa;
        /* abu-abu muda bawaan bootstrap */
        border: 1px solid #999 !important;
    }

    .table tbody td {
        border: 1px solid #999 !important;
    }

    table {
        border-collapse: collapse !important;
    }
</style>


<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}

$page = "transaksi";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';

// ✅ Proses update status transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int) ($_POST['id'] ?? 0);
    $aksi = $_POST['aksi'] ?? '';

    if ($id > 0) {
        if ($aksi === 'bayar') {
            $tgl_bayar = date('Y-m-d H:i:s');
            $q = "UPDATE tb_transaksi 
                  SET dibayar='dibayar', status='proses', tgl_bayar='$tgl_bayar'
                  WHERE id='$id'";
        } elseif ($aksi === 'selesai') {
            $q = "UPDATE tb_transaksi SET status='selesai' WHERE id='$id'";
        } elseif ($aksi === 'diambil') {
            $q = "UPDATE tb_transaksi SET status='diambil' WHERE id='$id'";
        }

        if (!empty($q)) {
            mysqli_query($connect, $q) or die(mysqli_error($connect));
            echo "<script>alert('Transaksi berhasil diperbarui!');window.location.href='index.php';</script>";
            exit();
        }
    }
}

// ✅ Ambil semua transaksi (sudah include member & pelanggan)
$qTrx = "
    SELECT t.*, 
           o.nama AS outlet_nama, 
           m.nama AS member_nama, 
           p.nama AS pelanggan_nama, 
           u.nama AS user_nama
    FROM tb_transaksi t
    LEFT JOIN tb_outlet o ON t.outlet_id = o.id
    LEFT JOIN tb_member m ON t.member_id = m.id
    LEFT JOIN tb_pelanggan p ON t.pelanggan_id = p.id
    LEFT JOIN tb_user u ON t.user_id = u.id
    ORDER BY t.id DESC
";
$result = mysqli_query($connect, $qTrx) or die(mysqli_error($connect));

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
    <?php include '../../partials/navbar.php'; ?>
    <div class="content" style="padding-top:90px;">
        <div class="page-inner">

            <div class="card shadow-lg">
                <div class="card-header d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                    <h5 class="mb-0">Tabel Transaksi</h5>
                    <a href="create.php" class="btn btn-primary">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                        <table id="transaksiTable" class="table table-bordered table-striped align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Invoice</th>
                                    <th class="text-center">Member</th>
                                    <th class="text-center">Pelanggan</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" >Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>


                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php $no = 1;
                                    while ($trx = $result->fetch_object()): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($trx->kode_invoice) ?></td>

                                            <!-- Kolom Member -->
                                            <td class="text-capitalize">
                                                <?= !empty($trx->member_nama) ? htmlspecialchars($trx->member_nama) : '-' ?>
                                            </td>

                                            <!-- Kolom Pelanggan -->
                                            <td class="text-capitalize">
                                                <?= !empty($trx->pelanggan_nama) ? htmlspecialchars($trx->pelanggan_nama) : '-' ?>
                                            </td>

                                            <td><?= date('d-m-Y', strtotime($trx->tgl)) ?></td>
                                            <td class="text-center">
                                                <?php if ($trx->status === 'proses'): ?>
                                                    <span class="badge bg-info text-dark">Proses</span>
                                                <?php elseif ($trx->status === 'selesai'): ?>
                                                    <span class="badge bg-success">Selesai</span>
                                                <?php elseif ($trx->status === 'diambil'): ?>
                                                    <span class="badge bg-dark">Diambil</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Belum Dibayar</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>Rp<?= number_format((float)$trx->total_bayar, 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <!-- Tombol Dinamis -->
                                                <?php if ($trx->dibayar === 'belum_dibayar'): ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $trx->id ?>">
                                                        <input type="hidden" name="aksi" value="bayar">
                                                        <button class="btn btn-info btn-sm"
                                                            onclick="return confirm('Konfirmasi pembayaran?')">
                                                            Bayar
                                                        </button>
                                                    </form>
                                                <?php elseif ($trx->dibayar === 'dibayar' && $trx->status === 'proses'): ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $trx->id ?>">
                                                        <input type="hidden" name="aksi" value="selesai">
                                                        <button class="btn btn-warning btn-sm"
                                                            onclick="return confirm('Ubah status ke selesai?')">
                                                            Selesai
                                                        </button>
                                                    </form>
                                                <?php elseif ($trx->status === 'selesai'): ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="id" value="<?= $trx->id ?>">
                                                        <input type="hidden" name="aksi" value="diambil">
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="return confirm('Konfirmasi sudah diambil?')">
                                                            Diambil
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <!-- Tombol Detail -->
                                                <a href="detail.php?id=<?= $trx->id ?>" class="btn btn-success btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="../../actions/transaksi/destroy.php?id=<?= $trx->id ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada transaksi</td>
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

<!-- Aktifkan DataTables -->
<script>
    $(document).ready(function() {
        $('#transaksiTable').DataTable({
            pageLength: 5, // default tampil 5
            lengthMenu: [5, 10, 25, 50, 100],
            responsive: true,
            ordering: true,
            searching: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            }
        });
    });
</script>