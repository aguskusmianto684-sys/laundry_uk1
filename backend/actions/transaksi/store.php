<?php
session_start();
include __DIR__ . '/../../../config/connection.php';

if (isset($_POST['simpan'])) {
    $kode_invoice   = mysqli_real_escape_string($connect, $_POST['kode_invoice']);
    $outlet_id      = (int) $_POST['outlet_id'];
    $tgl            = $_POST['tgl'];
    $batas_waktu    = $_POST['batas_waktu'];

    $biaya_tambahan = preg_replace('/[^0-9]/', '', $_POST['biaya_tambahan'] ?? '0');
    $biaya_tambahan = (int) $biaya_tambahan;

    $diskon         = (float) ($_POST['diskon'] ?? 0);
    $pajak          = (float) ($_POST['pajak'] ?? 0);

    $user_id = $_SESSION['user_id'] ?? 1;

    // --- Member ---
    $member_id = $_POST['member_id'] ?? null;
    if ($member_id === "new") {
        $nama   = mysqli_real_escape_string($connect, $_POST['new_member_nama']);
        $alamat = mysqli_real_escape_string($connect, $_POST['new_member_alamat']);
        $jk     = $_POST['new_member_jk'];
        $tlp    = $_POST['new_member_tlp'];

        $qInsertMem = "INSERT INTO tb_member (nama, alamat, jenis_kelamin, tlp)
                       VALUES ('$nama','$alamat','$jk','$tlp')";
        mysqli_query($connect, $qInsertMem) or die("Gagal insert member: " . mysqli_error($connect));
        $member_id = mysqli_insert_id($connect);
    }

    // --- Pelanggan ---
    $pelanggan_id = $_POST['pelanggan_id'] ?? null;
    if ($pelanggan_id === "new") {
        $nama   = mysqli_real_escape_string($connect, $_POST['new_pelanggan_nama']);
        $alamat = mysqli_real_escape_string($connect, $_POST['new_pelanggan_alamat']);
        $jk     = $_POST['new_pelanggan_jk'];
        $tlp    = $_POST['new_pelanggan_tlp'];

        $qInsertPel = "INSERT INTO tb_pelanggan (nama, alamat, jenis_kelamin, tlp)
                       VALUES ('$nama','$alamat','$jk','$tlp')";
        mysqli_query($connect, $qInsertPel) or die("Gagal insert pelanggan: " . mysqli_error($connect));
        $pelanggan_id = mysqli_insert_id($connect);
    }

    // Pastikan null kalau kosong
    $member_id    = !empty($member_id) ? "'$member_id'" : "NULL";
    $pelanggan_id = !empty($pelanggan_id) ? "'$pelanggan_id'" : "NULL";

    // Insert ke tb_transaksi
    $qTrx = "INSERT INTO tb_transaksi 
                (kode_invoice, outlet_id, member_id, pelanggan_id, user_id, tgl, batas_waktu, 
                 biaya_tambahan, diskon, pajak, status, dibayar, total_bayar) 
             VALUES 
                ('$kode_invoice', '$outlet_id', $member_id, $pelanggan_id, '$user_id', '$tgl', '$batas_waktu', 
                 '$biaya_tambahan', '$diskon', '$pajak', 'baru', 'belum_dibayar', 0)";
    if (mysqli_query($connect, $qTrx)) {
        $transaksi_id = mysqli_insert_id($connect);

        $total_bayar = 0;

        // Detail paket
        if (!empty($_POST['paket_id'])) {
            $paket_id   = $_POST['paket_id'];
            $qty        = $_POST['qty'];
            $ket        = $_POST['keterangan'];

            for ($i = 0; $i < count($paket_id); $i++) {
                $pid   = (int) $paket_id[$i];
                $q     = (int) $qty[$i];
                $k     = mysqli_real_escape_string($connect, $ket[$i]);

                $resHarga = mysqli_query($connect, "SELECT harga FROM tb_paket WHERE id='$pid'");
                $rowHarga = mysqli_fetch_assoc($resHarga);
                $harga    = (int) $rowHarga['harga'];

                $sub = $q * $harga;
                $total_bayar += $sub;

                $qDetail = "INSERT INTO tb_detail_transaksi (transaksi_id, paket_id, qty, keterangan) 
                            VALUES ('$transaksi_id', '$pid', '$q', '$k')";
                mysqli_query($connect, $qDetail) or die("Error detail: " . mysqli_error($connect));
            }
        }

        // Hitung total bayar
        $total_bayar = $total_bayar + $biaya_tambahan;
        if ($diskon > 0) {
            $total_bayar -= ($total_bayar * ($diskon / 100));
        }
        if ($pajak > 0) {
            $total_bayar += ($total_bayar * ($pajak / 100));
        }

        mysqli_query($connect, "UPDATE tb_transaksi SET total_bayar='$total_bayar' WHERE id='$transaksi_id'");

        echo "<script>
                alert('Transaksi berhasil ditambahkan!');
                window.location.href='../../pages/transaksi/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal simpan transaksi: " . mysqli_error($connect) . "');
                window.location.href='../../pages/transaksi/create.php';
              </script>";
    }
} else {
    header("Location: ../../pages/transaksi/index.php");
    exit();
}
?>
