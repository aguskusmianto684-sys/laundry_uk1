<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<?php
include '../config/koneksi.php';

$q = mysqli_query($connect, "
SELECT 
    t.id AS id_transaksi,
    t.kode_invoice,
    m.nama AS nama_member,
    u.nama AS nama_kasir,
    t.tgl,
    t.status,
    SUM(dt.qty * p.harga) AS total_bayar
FROM tb_transaksi t
INNER JOIN tb_member m ON t.member_id = m.id
INNER JOIN tb_user u ON t.user_id = u.id
INNER JOIN tb_detail_transaksi dt ON t.id = dt.transaksi_id
INNER JOIN tb_paket p ON dt.paket_id = p.id
GROUP BY t.id
");
?>

<body>
    <div class="container pt-5">
        <h2 class="text-center mb-5">Laporan Keuangan</h2>

        <table class="table table-bordered">
            <tr class="table-dark">
                <th>No</th>
                <th>Nama Member</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
            <?php $no = 1;
            while ($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama_member'] ?></td>
                    <td><?= $row['tgl'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['total_bayar'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>


    <script>
        window.print();
    </script>
</body>