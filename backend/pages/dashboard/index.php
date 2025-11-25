<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}
$page = 'dashboard';
include '../../partials/header.php';
include '../../partials/sidebar.php';

// pastikan path include benar relatif terhadap file ini
include __DIR__ . '/../../../config/connection.php';

// Ambil nama outlet pertama (atau sesuai kebutuhan)
$qOutlet = "SELECT nama FROM tb_outlet LIMIT 1";
$rOutlet = mysqli_query($connect, $qOutlet);

if ($rOutlet && mysqli_num_rows($rOutlet) > 0) {
    $row = mysqli_fetch_assoc($rOutlet);
    $namaOutlet = $row['nama'];
}

// inisialisasi default agar warning hilang walau query gagal
$totalTransaksi = 0;
$totalMember    = 0;
$totalPaket     = 0;
$totalOutlet    = 0;

// jika koneksi tersedia, jalankan query dengan pengecekan error
if (isset($connect)) {
    // total transaksi
    $q1 = "SELECT COUNT(*) AS total FROM tb_transaksi";
    $r1 = mysqli_query($connect, $q1);
    if ($r1) {
        $row1 = mysqli_fetch_assoc($r1);
        $totalTransaksi = (int) ($row1['total'] ?? 0);
    }

    // total member
    $q2 = "SELECT COUNT(*) AS total FROM tb_member";
    $r2 = mysqli_query($connect, $q2);
    if ($r2) {
        $row2 = mysqli_fetch_assoc($r2);
        $totalMember = (int) ($row2['total'] ?? 0);
    }

    // total paket
    $q3 = "SELECT COUNT(*) AS total FROM tb_paket";
    $r3 = mysqli_query($connect, $q3);
    if ($r3) {
        $row3 = mysqli_fetch_assoc($r3);
        $totalPaket = (int) ($row3['total'] ?? 0);
    }

    // total outlet
    $q4 = "SELECT COUNT(*) AS total FROM tb_outlet";
    $r4 = mysqli_query($connect, $q4);
    if ($r4) {
        $row4 = mysqli_fetch_assoc($r4);
        $totalOutlet = (int) ($row4['total'] ?? 0);
    }
}

// ===== Statistik Pendapatan per Bulan per Outlet =====

$outletData = [];
$outletList = [];
$labelsJSON = json_encode([]);
$datasetsJSON = json_encode([]);

$qIncome = "SELECT o.nama AS outlet, MONTH(t.tgl) AS bulan, SUM(t.total_bayar) AS total
            FROM tb_transaksi t
            JOIN tb_outlet o ON t.outlet_id = o.id
            WHERE YEAR(t.tgl) = YEAR(CURDATE())
            GROUP BY o.id, MONTH(t.tgl)
            ORDER BY o.id, bulan";

$resIncome = mysqli_query($connect, $qIncome);

$namaBulan = [
    1 => 'Jan',
    2 => 'Feb',
    3 => 'Mar',
    4 => 'Apr',
    5 => 'Mei',
    6 => 'Jun',
    7 => 'Jul',
    8 => 'Agu',
    9 => 'Sep',
    10 => 'Okt',
    11 => 'Nov',
    12 => 'Des'
];

// isi data outlet
if ($resIncome) {
    while ($row = mysqli_fetch_assoc($resIncome)) {
        $outlet = $row['outlet'];
        $bulan = (int)$row['bulan'];
        $total = (int)$row['total'];

        if (!isset($outletData[$outlet])) {
            $outletData[$outlet] = array_fill(1, 12, 0);
            $outletList[] = $outlet;
        }
        $outletData[$outlet][$bulan] = $total;
    }
}

// ===== Ringkasan per outlet =====
$outletSummary = [];

foreach ($outletData as $outlet => $data) {
    $total = array_sum($data);
    $jmlBulanAda = count(array_filter($data)); // bulan yg ada transaksi
    $rata = $jmlBulanAda > 0 ? $total / $jmlBulanAda : 0;

    // cari bulan dengan nilai maksimum
    $maxVal = max($data);
    $bulanMax = $maxVal > 0 ? $namaBulan[array_search($maxVal, $data)] : '-';

    // cari bulan dengan nilai minimum (tapi hanya dari bulan yang ada transaksi)
    $filtered = array_filter($data); // buang nilai 0
    $minVal = 0;
    $bulanMin = '-';

    if (!empty($filtered)) {
        $minVal = min($filtered);
        $bulanMin = $namaBulan[array_search($minVal, $data)];
    }

    $outletSummary[$outlet] = [
        'total'    => $total,
        'rata'     => $rata,
        'max'      => $maxVal,
        'bulanMax' => $bulanMax,
        'min'      => $minVal,
        'bulanMin' => $bulanMin
    ];
}



// ubah ke JSON untuk Chart.js
$labelsJSON = json_encode(array_values($namaBulan));

$datasets = [];
$colors = [
    'rgba(0, 123, 255, 0.6)', // biru
    'rgba(40, 167, 69, 0.6)', // hijau
    'rgba(255, 193, 7, 0.6)', // kuning
    'rgba(220, 53, 69, 0.6)', // merah
    'rgba(23, 162, 184, 0.6)' // cyan
];

$i = 0;
foreach ($outletData as $outlet => $data) {
    $datasets[] = [
        "label" => $outlet,
        "data" => array_values($data),
        "backgroundColor" => $colors[$i % count($colors)],
        "borderColor" => str_replace("0.6", "1", $colors[$i % count($colors)]),
        "borderWidth" => 1
    ];
    $i++;
}

$datasetsJSON = json_encode($datasets);

// ===== Hitung ringkasan untuk keterangan =====
$totalPendapatan = 0;
$rataPendapatan = 0;
$maxPendapatan = 0;
$bulanMax = '-';

$qStat = "SELECT MONTH(t.tgl) AS bulan, SUM(t.total_bayar) AS total
          FROM tb_transaksi t
          WHERE YEAR(t.tgl) = YEAR(CURDATE())
          GROUP BY MONTH(t.tgl)";
$resStat = mysqli_query($connect, $qStat);

$jmlBulan = 0;
while ($row = mysqli_fetch_assoc($resStat)) {
    $totalPendapatan += $row['total'];
    $jmlBulan++;
    if ($row['total'] > $maxPendapatan) {
        $maxPendapatan = $row['total'];
        $bulanMax = $namaBulan[(int)$row['bulan']];
    }
}

$rataPendapatan = $jmlBulan > 0 ? $totalPendapatan / $jmlBulan : 0;




?>

<style>
    /* Garis tabel lebih tipis dan halus */
    .table,
    .table-bordered,
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #999 !important;
        color: #000;
    }

    .table thead th {
        background-color: #f8f9fa;
        border: 1px solid #999 !important;
    }

    .table tbody td {
        border: 1px solid #999 !important;
    }

    table {
        border-collapse: collapse !important;
    }
</style>

<!-- End Sidebar -->
<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?> <!-- navbar -->
    <div class="container">
        <div class="page-inner">
            <!-- Card Welcome -->
            <div class="col-md-12 mb-4">
                <div class="card text-white" style="background: linear-gradient(135deg, #011936, #023e8a); border: none;">
                    <div class="card-body text-center py-5">
                        <h1 class="fw-bold">SELAMAT DATANG</h1>
                        <h3 class="mb-0 text-uppercase">Di Laundry Fresh</h3>
                        <div id="datetime" class="mt-2 fw-semibold"></div>
                    </div>
                </div>
            </div>

            <!-- Statistik kecil -->
            <div class="row">
                <!-- Card transaksi -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round" style="border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Transaksi</p>
                                        <h4 class="card-title"><?= $totalTransaksi ?></h4>
                                        <a href="../transaksi/index.php" class="text-decoration-none link-primary">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card member -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round" style="border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Member</p>
                                        <h4 class="card-title"><?= $totalMember ?></h4>
                                        <a href="../pelanggan/index.php" class="text-decoration-none link-primary">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card outlet -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round" style="border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-store"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Outlet</p>
                                        <h4 class="card-title"><?= $totalOutlet ?></h4>
                                        <a href="../outlet/index.php" class="text-decoration-none link-primary">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card paket -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round" style="border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Paket</p>
                                        <h4 class="card-title"><?= $totalPaket ?></h4>
                                        <a href="../paket/index.php" class="text-decoration-none link-primary">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
                <div class="col-md-12 mb-4">
                    <div class="card" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        <div class="card-header text-white"
                            style="background: linear-gradient(135deg, #023e8a, #0077b6, #90e0ef); border: none;">
                            <h5 class="mb-0">Statistik Pendapatan per Bulan (<?= date('Y') ?>)</h5>
                        </div>

                        <div class="card-body">
                            <!-- Baris 1: Grafik -->
                            <div class="row mb-4">
                                <div class="col-12" style="height:300px;">
                                    <canvas id="incomeChart"></canvas>
                                </div>
                            </div>

                            <!-- Baris 2: Ringkasan per Outlet -->
                            <div class="row">
                                <?php foreach ($outletSummary as $outlet => $s): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3" style="border:1px solid #ddd; border-radius:8px; background:#f8f9fa;">
                                            <strong><?= $outlet ?></strong>
                                            <p class="mb-1"><small>Total: Rp<?= number_format($s['total'], 0, ',', '.') ?></small></p>
                                            <p class="mb-1"><small>Rata-rata / Bulan: Rp<?= number_format($s['rata'], 0, ',', '.') ?></small></p>
                                            <p class="mb-1"><small>Tertinggi: Rp<?= number_format($s['max'], 0, ',', '.') ?> (<?= $s['bulanMax'] ?>)</small></p>
                                            <p class="mb-0"><small>Terendah: Rp<?= number_format($s['min'], 0, ',', '.') ?> (<?= $s['bulanMin'] ?>)</small></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>



<!-- Recent Transactions -->
<div class="col-md-12 mb-4">
    <div class="card" style="border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
        <div class="card-header d-flex justify-content-between align-items-center text-white"
            style="background: linear-gradient(135deg, #023e8a, #0077b6, #90e0ef); border: none;">
            <h5 class="mb-0">Transaksi Terbaru</h5>
            <a href="../transaksi/index.php" class="btn btn-sm btn-primary" style="padding: 0.25rem 0.5rem;">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="text-capitalize text-center">
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Member</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $qtrx = "SELECT t.*, 
                                        m.nama AS member_nama,
                                        p.nama AS pelanggan_nama
                                 FROM tb_transaksi t
                                 LEFT JOIN tb_member m ON t.member_id = m.id
                                 LEFT JOIN tb_pelanggan p ON t.pelanggan_id = p.id
                                 ORDER BY t.id DESC LIMIT 5";
                        $restrx = mysqli_query($connect, $qtrx);

                        if ($restrx && $restrx->num_rows > 0) {
                            $no = 1;
                            while ($trx = $restrx->fetch_object()) {
                                $invoice   = $trx->kode_invoice ?? '-';
                                $member    = $trx->member_nama ?? '-';
                                $pelanggan = $trx->pelanggan_nama ?? '-';
                                $tgl       = $trx->tgl ? date("d-m-Y", strtotime($trx->tgl)) : '-';
                                $status    = $trx->status ?? '-';
                                $total     = $trx->total_bayar ? "Rp" . number_format($trx->total_bayar, 0, ',', '.') : 'Rp0';

                                if ($status === 'baru') {
                                    $badge = "<span class='badge bg-danger'>Baru</span>";
                                } elseif ($status === 'proses') {
                                    $badge = "<span class='badge bg-info text-dark'>Proses</span>";
                                } elseif ($status === 'selesai') {
                                    $badge = "<span class='badge bg-success'>Selesai</span>";
                                } elseif ($status === 'diambil') {
                                    $badge = "<span class='badge bg-dark'>Diambil</span>";
                                } else {
                                    $badge = "<span class='badge bg-secondary'>-</span>";
                                }

                                echo "<tr>
                                        <td class='text-center'>$no</td>
                                        <td class='text-center'>" . htmlspecialchars($invoice) . "</td>
                                        <td class='text-center text-capitalize'>" . htmlspecialchars($member) . "</td>
                                        <td class='text-center text-capitalize'>" . htmlspecialchars($pelanggan) . "</td>
                                        <td class='text-center'>$tgl</td>
                                        <td class='text-center'>$badge</td>
                                        <td class='text-center'>$total</td>
                                      </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Tidak ada transaksi terbaru</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


        </div>
    </div>

    <?php
    include '../../partials/footer.php';
    include '../../partials/script.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labelsJSON ?>,
                datasets: <?= $datasetsJSON ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>