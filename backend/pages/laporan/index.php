<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'owner') {
}
if (!isset($_SESSION['logged_in'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href='../user/login.php';
    </script>";
    exit();
}

$page = "laporan"; // halaman aktif
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';

// ambil filter tanggal (kalau ada)
$mulai = $_GET['mulai'] ?? '';
$sampai = $_GET['sampai'] ?? '';

// query transaksi (JOIN member & pelanggan)
$qLaporan = "
    SELECT t.id, 
           t.kode_invoice, 
           m.nama AS member_nama, 
           p.nama AS pelanggan_nama,
           t.tgl, 
           t.status, 
           t.dibayar,
           t.total_bayar
    FROM tb_transaksi t
    LEFT JOIN tb_member m ON t.member_id = m.id
    LEFT JOIN tb_pelanggan p ON t.pelanggan_id = p.id
    WHERE 1=1
";
if ($mulai && $sampai) {
    $qLaporan .= " AND DATE(t.tgl) BETWEEN '$mulai' AND '$sampai'";
}
$qLaporan .= " ORDER BY t.tgl DESC";

$result = mysqli_query($connect, $qLaporan) or die(mysqli_error($connect));

?>

<style>
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
            <div class="card shadow-lg">
                <div class="card-header d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                    <h5 class="mb-0">Laporan Transaksi</h5>
                </div>
                <div class="card-body">

                    <!-- form filter -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label>Dari Tanggal</label>
                            <input type="date" name="mulai" value="<?= $mulai ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="sampai" value="<?= $sampai ?>" class="form-control">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="index.php" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>

                    <!-- tabel laporan -->
                    <div class="table-responsive">
                        <table id="laporanTable" class="table table-bordered table-striped align-middle text-center">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Invoice</th>
                                    <th class="text-center">Member</th>
                                    <th class="text-center">Pelanggan</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Total Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    while ($row = $result->fetch_object()): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row->kode_invoice) ?></td>
                                            <td class="text-capitalize"><?= !empty($row->member_nama) ? htmlspecialchars($row->member_nama) : '-' ?></td>
                                            <td class="text-capitalize"><?= !empty($row->pelanggan_nama) ? htmlspecialchars($row->pelanggan_nama) : '-' ?></td>
                                            <td><?= date('d-m-Y', strtotime($row->tgl)) ?></td>
                                            <td><?= ucfirst($row->status) ?></td>
                                            <td>Rp<?= number_format($row->total_bayar, 0, ',', '.') ?></td>
                                        </tr>
                                        <?php
                                        // ✅ hanya dihitung kalau sudah dibayar
                                        if ($row->dibayar === 'dibayar') {
                                            $total += $row->total_bayar;
                                        }
                                        ?>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-muted">Tidak ada data transaksi</td>
                                    </tr>
                                    <?php $total = 0; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="6" style="text-align:right;">Total Pendapatan</td>
                                    <td>Rp<?= number_format($total ?? 0, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>

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
        $('#laporanTable').DataTable({
            "pageLength": 7,
            "lengthMenu": [7, 10, 25, 50],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari total _MAX_ data)"
            },
            dom: '<"d-flex justify-content-between align-items-center mb-2"Bf>rtip',
            buttons: [{
                    extend: 'copy',
                    text: '<i class="bi bi-clipboard"></i> Copy',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'csv',
                    text: '<i class="bi bi-filetype-csv"></i> CSV',
                    className: 'btn btn-info btn-sm text-white'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    title: 'Laporan Transaksi Laundry Fresh',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    footer: true,
                    customize: function(doc) {
                        doc.styles.title = {
                            alignment: 'center',
                            fontSize: 14,
                            bold: true
                        };
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableHeader.fontSize = 11;
                        doc.styles.tableHeader.fillColor = '#0077b6';
                        doc.styles.tableHeader.color = 'white';
                        doc.defaultStyle.alignment = 'center';
                        doc.defaultStyle.fontSize = 10;
                        doc.pageMargins = [40, 60, 40, 40];
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) {
                            return 0.5;
                        };
                        objLayout['vLineWidth'] = function(i) {
                            return 0.5;
                        };
                        objLayout['hLineColor'] = function(i) {
                            return '#000';
                        };
                        objLayout['vLineColor'] = function(i) {
                            return '#000';
                        };
                        objLayout['paddingLeft'] = function(i) {
                            return 4;
                        };
                        objLayout['paddingRight'] = function(i) {
                            return 4;
                        };
                        doc.content[1].layout = objLayout;

                        // ✅ ambil baris terakhir (footer)
                        var rowCount = doc.content[1].table.body.length;
                        var footerRow = doc.content[1].table.body[rowCount - 1];

                        // simpan nilai total
                        var totalText = footerRow[footerRow.length - 1].text;

                        // kosongkan baris footer
                        doc.content[1].table.body[rowCount - 1] = [];

                        // isi ulang footer → 1 kolom label + 1 kolom total
                        doc.content[1].table.body[rowCount - 1].push({
                            text: 'Total Pendapatan',
                            colSpan: footerRow.length - 1,
                            alignment: 'right',
                            bold: true
                        });

                        // kasih cell kosong buat span
                        for (var i = 1; i < footerRow.length - 1; i++) {
                            doc.content[1].table.body[rowCount - 1].push({});
                        }

                        // kolom terakhir isi angka total
                        doc.content[1].table.body[rowCount - 1].push({
                            text: totalText,
                            alignment: 'right',
                            bold: true
                        });
                    }

                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-dark btn-sm',
                    title: 'Laporan Transaksi Laundry Fresh',
                    footer: true,
                    customize: function(win) {
                        $(win.document.body).css('text-align', 'center');
                        $(win.document.body).find('table')
                            .addClass('display')
                            .css('margin', '0 auto')
                            .css('border-collapse', 'collapse')
                            .css('width', '100%');
                        $(win.document.body).find('table th, table td')
                            .css('border', '1px solid black')
                            .css('padding', '6px');

                        // ✅ Gabungkan footer print
                        var $footerRow = $(win.document.body).find('table tfoot tr');
                        var $cells = $footerRow.find('td');

                        // kosongkan semua dulu
                        $cells.text('');

                        // cell pertama jadi label + colspan
                        $cells.eq(0)
                            .attr('colspan', $cells.length - 1)
                            .css('text-align', 'right')
                            .text('Total Pendapatan');

                        // isi angka total di kolom terakhir
                        $cells.eq($cells.length - 1)
                            .text("<?= 'Rp' . number_format($total ?? 0, 0, ',', '.') ?>")
                            .css('font-weight', 'bold');
                    }
                }
            ]
        });
    });
</script>