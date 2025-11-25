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

// ✅ Ambil ID transaksi
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    echo "<script>alert('ID tidak valid');window.location.href='index.php';</script>";
    exit();
}

// ✅ Data transaksi (tambahkan pelanggan juga)
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
    WHERE t.id = '$id'
    LIMIT 1
";
$resTrx = mysqli_query($connect, $qTrx);
$trx = mysqli_fetch_object($resTrx);
if (!$trx) {
    echo "<script>alert('Transaksi tidak ditemukan');window.location.href='index.php';</script>";
    exit();
}

// ✅ Detail paket
$qDetail = "
    SELECT d.*, p.nama_paket, p.harga, d.keterangan
    FROM tb_detail_transaksi d
    LEFT JOIN tb_paket p ON d.paket_id = p.id
    WHERE d.transaksi_id = '$id'
";
$resDetail = mysqli_query($connect, $qDetail);

$total = 0;
$items = [];
while ($row = mysqli_fetch_object($resDetail)) {
    $harga = (float)$row->harga;
    $qty = (float)$row->qty;
    $subtotal_item = $harga * $qty;
    $total += $subtotal_item;
    $row->subtotal = $subtotal_item;
    $items[] = $row;
}

// ✅ Hitung diskon, pajak, grand total
$diskon = ($trx->diskon / 100) * $total;
$pajak = ($trx->pajak / 100) * ($total - $diskon);
$grand_total = $total - $diskon + $pajak + (float)$trx->biaya_tambahan;
?>

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
</style>

<!-- Main Panel -->
<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?>
    <div class="content" style="padding-top:90px;">
        <div class="page-inner">

            <div class="card shadow-lg mx-auto nota-area" style="max-width: 800px;">
                <div class="card-header text-center"
                    style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                    <h5 class="mb-0">Nota Transaksi</h5>
                </div>
                <div class="card-body" id="notaArea">

                    <!-- Info Transaksi -->
                    <table class="table table-borderless">
                        <tr>
                            <th style="width:200px;">Outlet</th>
                            <td><?= htmlspecialchars($trx->outlet_nama) ?></td>
                        </tr>
                        <tr>
                            <th>Kode Invoice</th>
                            <td><?= htmlspecialchars($trx->kode_invoice) ?></td>
                        </tr>
                        <tr>
                            <th>Member</th>
                            <td><?= !empty($trx->member_nama) ? htmlspecialchars($trx->member_nama) : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Pelanggan</th>
                            <td><?= !empty($trx->pelanggan_nama) ? htmlspecialchars($trx->pelanggan_nama) : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td><?= htmlspecialchars($trx->tgl) ?></td>
                        </tr>
                        <tr>
                            <th>Batas Waktu</th>
                            <td><?= htmlspecialchars($trx->batas_waktu) ?></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td><?= htmlspecialchars($trx->keterangan ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?= ucfirst($trx->status) ?></td>
                        </tr>
                        <tr>
                            <th>Pembayaran</th>
                            <td><?= ucfirst($trx->dibayar) ?></td>
                        </tr>
                        <tr>
                            <th>Kasir</th>
                            <td><?= htmlspecialchars($trx->user_nama) ?></td>
                        </tr>
                    </table>

                    <!-- Rincian Paket -->
                    <h5 class="mt-4">Rincian Paket</h5>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Paket</th>
                                <th>Keterangan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $it): ?>
                                <tr>
                                    <td><?= htmlspecialchars($it->nama_paket) ?></td>
                                    <td><?= htmlspecialchars($it->keterangan ?? '-') ?></td>
                                    <td class="text-center"><?= $it->qty ?></td>
                                    <td class="text-end">Rp<?= number_format($it->harga, 0, ',', '.') ?></td>
                                    <td class="text-end">Rp<?= number_format($it->subtotal, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Perhitungan -->
                    <table class="table">

                        <tr>
                            <th class="text-end">Diskon (<?= $trx->diskon ?>%)</th>
                            <td class="text-end">- Rp<?= number_format($diskon, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th class="text-end">Pajak (<?= $trx->pajak ?>%)</th>
                            <td class="text-end">Rp<?= number_format($pajak, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th class="text-end">Biaya Tambahan</th>
                            <td class="text-end">Rp<?= number_format($trx->biaya_tambahan, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th class="text-end">Subtotal</th>
                            <td class="text-end">Rp<?= number_format($total, 0, ',', '.') ?></td>
                        </tr>
                    </table>

                </div>
                <div class="card-footer text-center no-print">
                    <button class="btn btn-dark" onclick="window.print()">🖨️ Print</button>
                    <a href="index.php" class="btn btn-primary">⬅️ Kembali</a>
                </div>
            </div>

        </div>
    </div>
    <?php include '../../partials/footer.php'; ?>
</div>
<?php include '../../partials/script.php'; ?>

<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    body * {
        visibility: hidden !important;
        font-family: "Times New Roman", Arial, sans-serif;
    }

    .nota-area,
    .nota-area * {
        visibility: visible !important;
    }

    .nota-area {
        position: relative;
        left: 0;
        top: 0;
        transform: none;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0;
        margin: 0 auto;
        box-shadow: none !important;
        border: none !important;
    }

    .card-header {
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        background: none !important;
        color: #000 !important;
        border-bottom: 2px solid #000;
        margin-bottom: 10px;
    }

    .card-body {
        font-size: 11pt;
        line-height: 1.4;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 10px;
        font-size: 10pt;
    }

    table th,
    table td {
        border: 1px solid #000 !important;
        padding: 5px;
    }

    .table-borderless th,
    .table-borderless td {
        border: none !important;
        padding: 3px 5px;
    }

    .no-print {
        display: none !important;
    }
}
</style>
