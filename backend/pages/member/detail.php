<?php
$page = "member";
include __DIR__ . '/../../../config/connection.php';
include '../../partials/header.php';
include '../../partials/sidebar.php';

$id = (int) $_GET['id'];
$qMember = "SELECT * FROM tb_member WHERE id='$id' LIMIT 1";
$result  = mysqli_query($connect, $qMember);
$member  = mysqli_fetch_assoc($result);
?>
<style>
    .card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2); /* bayangan lebih tegas */
}
</style>

<!-- Main Panel -->
<div class="main-panel">
    <?php include '../../partials/navbar.php'; ?> <!-- navbar -->
    <div class="content" style="padding-top: 90px;">
        <div class="page-inner">
            <div class="card shadow-lg">
                <div class="card-header" style="background: linear-gradient(135deg, white, #0077b6, #90e0ef); border: none;">
                    <h5>Detail Member</h5>
                </div>
                <div class="card-body">
                    <?php if ($member): ?>
                        <p><strong>Nama:</strong> <?= htmlspecialchars($member['nama']) ?></p>
                        <p><strong>Alamat:</strong> <?= htmlspecialchars($member['alamat']) ?></p>
                        <p><strong>Jenis Kelamin:</strong> <?= htmlspecialchars($member['jenis_kelamin']) ?></p>
                        <p><strong>No. Telepon:</strong> <?= htmlspecialchars($member['tlp']) ?></p>
                        <a href="./index.php" class="btn btn-primary">Kembali</a>
                    <?php else: ?>
                        <p class="text-danger">Data tidak ditemukan.</p>
                        <a href="./index.php" class="btn btn-primary">Kembali</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../partials/footer.php'; ?>
</div>
<?php include '../../partials/script.php'; ?>
