<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<style>
  .main-header {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15); /* bayangan halus */
}

</style>

<!-- Main Header -->
<div class="main-header">

  <!-- Logo Header -->
  <div class="main-header-logo">
    <div class="logo-header" data-background-color="dark">
      <a href="../../pages/dashboard/index.php" class="logo">
        <img
          src="../../template_admin/assets/img/kaiadmin/logo_light.svg"
          alt="navbar brand"
          class="navbar-brand"
          height="20" />
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
          <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
          <i class="gg-menu-left"></i>
        </button>
      </div>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
  </div>
  <!-- End Logo Header -->

  <!-- Navbar Header -->
  <nav class="navbar navbar-header navbar-expand-lg border-bottom">
    <div class="container-fluid">


      <!-- Right Navbar -->
      <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

        <!-- User Menu -->
        <li class="nav-item topbar-user dropdown hidden-caret">
          <a
            class="dropdown-toggle profile-pic"
            data-bs-toggle="dropdown"
            href="#"
            aria-expanded="false">

            <span class="me-2 text-capitalize" style="font-weight: bold; font-size: 16px;">
              <?= htmlspecialchars($_SESSION['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8') ?>
            </span>



            <div class="avatar-sm d-flex justify-content-center align-items-center bg-light rounded-circle">
              <i class="fas fa-user-circle fa-2x text-dark"></i>
            </div>
          </a>

          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-sm d-flex justify-content-center align-items-center bg-light rounded-circle">
                    <i class="fas fa-user-circle fa-2x text-dark"></i>
                  </div>
                  <div class="u-text">
                    <h4 class="text-capitalize"><?= $_SESSION['username'] ?? 'Guest' ?></h4>
                    <a href="../../actions/auth/logout.php" class="btn btn-xs btn-danger btn-sm">Logout</a>
                  </div>

                </div>
              </li>
            </div>
          </ul>
        </li>
        <!-- End User Menu -->
      </ul>
    </div>
  </nav>
  <!-- End Navbar Header -->
</div>
<!-- End Main Header -->