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

// === Generate kode invoice ===
function generateInvoice($connect)
{
    $prefix = "INV";
    $q = mysqli_query($connect, "SELECT MAX(kode_invoice) AS max_inv FROM tb_transaksi");
    $row = mysqli_fetch_assoc($q);
    $last = $row['max_inv'];

    if ($last) {
        $lastNumber = intval(substr($last, 3));
        $next = $lastNumber + 1;
    } else {
        $next = 1;
    }

    return $prefix . str_pad($next, 3, "0", STR_PAD_LEFT);
}

$kode_invoice = generateInvoice($connect);
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

    <div class="content" style="padding-top: 90px;">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">

                        <div class="card-header" style="background: linear-gradient(135deg, white, #0077b6, #90e0ef);">
                            <h5 class="mb-0">Tambah Transaksi</h5>
                        </div>

                        <div class="card-body">
                            <form action="../../actions/transaksi/store.php" method="POST">

                                <div class="mb-3">
                                    <label>Kode Invoice</label>
                                    <input type="text" name="kode_invoice"
                                        value="<?= $kode_invoice ?>"
                                        class="form-control" readonly>
                                </div>

                                <div class="mb-3">
                                    <label>Outlet</label>
                                    <select name="outlet_id" class="form-control" required>
                                        <?php
                                        $qOut = mysqli_query($connect, "SELECT * FROM tb_outlet");
                                        while ($o = mysqli_fetch_assoc($qOut)): ?>
                                            <option value="<?= $o['id'] ?>"><?= $o['nama'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- Member -->
                                <div class="mb-3">
                                    <label>Member (Pelanggan)</label>
                                    <select id="memberSelect" name="member_id" class="form-control">
                                        <option value="">-- Tidak pakai Member --</option>
                                        <?php
                                        // ✅ hanya ambil 5 member terbaru
                                        $qMem = mysqli_query($connect, "SELECT * FROM tb_member ORDER BY id DESC LIMIT 5");
                                        while ($m = mysqli_fetch_assoc($qMem)): ?>
                                            <option value="<?= $m['id'] ?>"><?= $m['nama'] ?> (<?= $m['tlp'] ?>)</option>
                                        <?php endwhile; ?>
                                        <option value="new">+ Tambah Member Baru</option>
                                    </select>
                                </div>

                                <!-- Pelanggan -->
                                <div class="mb-3">
                                    <label>Pelanggan (Member)</label>
                                    <select id="pelangganSelect" name="pelanggan_id" class="form-control">
                                        <option value="">-- Tidak pakai Pelanggan --</option>
                                        <?php
                                        // ✅ hanya ambil 5 pelanggan terbaru
                                        $qPel = mysqli_query($connect, "SELECT * FROM tb_pelanggan ORDER BY id DESC LIMIT 5");
                                        while ($p = mysqli_fetch_assoc($qPel)): ?>
                                            <option value="<?= $p['id'] ?>"><?= $p['nama'] ?> (<?= $p['tlp'] ?>)</option>
                                        <?php endwhile; ?>
                                        <option value="new">+ Tambah Pelanggan Baru</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Tanggal</label>
                                    <input type="date" name="tgl" value="<?= date('Y-m-d') ?>" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Batas Waktu</label>
                                    <input type="date" name="batas_waktu" class="form-control" required>
                                </div>

                                <h5 class="mt-4">Paket Cucian</h5>
                                <div id="paket-area">
                                    <div class="d-flex mb-2">
                                        <select name="paket_id[]" class="form-select me-2" required>
                                            <?php
                                            $qPkt = mysqli_query($connect, "SELECT * FROM tb_paket");
                                            while ($p = mysqli_fetch_assoc($qPkt)): ?>
                                                <option value="<?= $p['id'] ?>" data-harga="<?= $p['harga'] ?>"><?= $p['nama_paket'] ?> - Rp<?= number_format($p['harga'], 0, ',', '.') ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                        <input type="number" name="qty[]" value="1" class="form-select me-2" style="width:100px;" required>
                                        <input type="text" name="keterangan[]" placeholder="Keterangan" class="form-control" style="width:200px;">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addPaket()">+ Tambah Paket</button>

                                <h5>Biaya Tambahan / Diskon / Pajak</h5>
                                <input type="text" id="biaya_tambahan" name="biaya_tambahan"
                                    placeholder="Rp 0" class="form-control mb-2">

                                <input type="number" name="diskon" placeholder="Diskon (%)" class="form-control mb-2">
                                <input type="number" name="pajak" placeholder="Pajak (%)" class="form-control mb-2">

                                <button type="submit" name="simpan" class="btn btn-success mt-3">Simpan</button>
                                <a href="./index.php" class="btn btn-primary mt-3">Kembali</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../partials/footer.php'; ?>
</div>

<?php include '../../partials/script.php'; ?>

<!-- ✅ Tambah CDN Select2 (di bawah footer sebelum </body>) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    function addPaket() {
        let area = document.getElementById('paket-area');
        let div = document.createElement('div');
        div.className = 'd-flex mb-2';
        div.innerHTML = area.firstElementChild.innerHTML;
        area.appendChild(div);
    }

    // ✅ Tampilkan form tambah Member/Pelanggan baru jika dipilih "new"
    document.getElementById('memberSelect').addEventListener('change', function() {
        document.getElementById('newMemberForm').style.display = this.value === 'new' ? 'block' : 'none';
    });
    document.getElementById('pelangganSelect').addEventListener('change', function() {
        document.getElementById('newPelangganForm').style.display = this.value === 'new' ? 'block' : 'none';
    });

    // ✅ Format input rupiah
    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return rupiah ? "Rp " + rupiah : "";
    }
    document.getElementById("biaya_tambahan").addEventListener("keyup", function(e) {
        this.value = formatRupiah(this.value);
    });

    // ✅ Aktifkan Select2 untuk Member & Pelanggan
    $(document).ready(function() {
        $('#memberSelect').select2({
            placeholder: "Cari Member...",
            allowClear: true,
            width: '100%' // biar full lebar
        });

        $('#pelangganSelect').select2({
            placeholder: "Cari Pelanggan...",
            allowClear: true,
            width: '100%' // biar full lebar
        });
    });
</script>