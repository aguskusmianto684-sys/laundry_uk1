<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<style>
  /* Warna dan gaya nav active */
  .sidebar .nav .nav-item.active>a {
    background: #4e73df;
    color: #fff !important;
    font-weight: bold;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  }

  /* Icon di nav active */
  .sidebar .nav .nav-item.active i {
    color: #fff !important;
  }
</style>

<!-- Sidebar -->
<div class="sidebar" data-background-color="white">
  <div class="sidebar-logo">
    <div class="logo-header mt-4" data-background-color="white">
      <p href="/pkl_lauwba/laundry_uk1/backend/pages/dashboard/index.php" class="logo">
        <img src="/pkl_lauwba/laundry_uk1/backend/template_admin/assets/img/kaiadmin/logo.png"
          alt="navbar brand"
          class="navbar-brand"
          height="185" />
      </p>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
  </div>

  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <!-- Selalu tampil -->
        <li class="nav-item <?= ($page == 'dashboard') ? 'active' : '' ?>">
          <a href="/pkl_lauwba/laundry_uk1/backend/pages/dashboard/index.php">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Admin -->
        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li class="nav-item <?= ($page == 'outlet') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/outlet/index.php">
              <i class="fas fa-store"></i>
              <p>Outlet</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'paket') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/paket/index.php">
              <i class="fas fa-layer-group"></i>
              <p>Paket</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'pengguna') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/pengguna/index.php">
              <i class="fas fa-user"></i>
              <p>Pengguna</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'member') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/member/index.php">
              <i class="fas fa-id-card"></i>
              <p>Member</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'pelanggan') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/pelanggan/index.php">
              <i class="fas fa-users"></i>
              <p>Pelanggan</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'transaksi') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/transaksi/index.php">
              <i class="fas fa-receipt"></i>
              <p>Transaksi</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'laporan') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/laporan/index.php">
              <i class="fas fa-file-alt"></i>
              <p>Laporan</p>
            </a>
          </li>
        <?php endif; ?>

        <!-- Kasir -->
        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'kasir'): ?>
          <li class="nav-item <?= ($page == 'member') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/member/index.php">
              <i class="fas fa-id-card"></i>
              <p>Member</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'member') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/member/index.php">
              <i class="fas fa-id-card"></i>
              <p>Pelanggan</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'transaksi') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/transaksi/index.php">
              <i class="fas fa-receipt"></i>
              <p>Transaksi</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'laporan') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/laporan/index.php">
              <i class="fas fa-file-alt"></i>
              <p>Laporan</p>
            </a>
          </li>
        <?php endif; ?>

        <!-- Owner -->
        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'owner'): ?>
          <li class="nav-item <?= ($page == 'laporan') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/laporan/index.php">
              <i class="fas fa-file-alt"></i>
              <p>Laporan</p>
            </a>
          </li>
          <li class="nav-item <?= ($page == 'aktivitas') ? 'active' : '' ?>">
            <a href="/pkl_lauwba/laundry_uk1/backend/pages/aktivitas/index.php">
              <i class="fas fa-tasks"></i>
              <p>Aktivitas</p>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>