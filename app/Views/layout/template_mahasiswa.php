<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Sistem Pengajuan Skripsi' ?></title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style AdminLTE -->
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
</head>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <!-- Angka notifikasi -->
                        <span class="badge badge-warning navbar-badge">1</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">1 Notifikasi</span>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('mahasiswa/status') ?>" class="dropdown-item">
                            <i class="fas fa-info-circle mr-2"></i> Status pengajuan diperbarui
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                    </div>
                </li>
                
                <!-- User Profile -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-circle mr-1 text-secondary"></i> 
                        <span class="font-weight-bold text-dark"><?= session()->get('username') ?></span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="<?= base_url('admin/dashboard') ?>" class="brand-link text-center d-flex align-items-center justify-content-center" style="height: calc(3.5rem + 1px); border-bottom: 1px solid #4f5962; padding: 0;">
                <span class="brand-text font-weight-light m-0">
                    <h4 class="m-0" style="font-size: 1.3rem; line-height: 1;"><strong>SPS</strong> <small class="text-muted">Mahasiswa</small></h4>
                </span>
            </a>

            <!-- Sidebar Menu -->
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        
                        <!-- Header Menu Utama -->
                        <li class="nav-header">MENU UTAMA</li>

                        <li class="nav-item">
                            <a href="<?= base_url('mahasiswa/dashboard') ?>" class="nav-link <?= service('uri')->getSegment(2) == 'dashboard' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('mahasiswa/pengajuan') ?>" class="nav-link <?= service('uri')->getSegment(2) == 'pengajuan' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-signature"></i>
                                <p>Pengajuan Skripsi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('mahasiswa/status') ?>" class="nav-link <?= service('uri')->getSegment(2) == 'status' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-search"></i>
                                <p>Status Pengajuan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('mahasiswa/riwayat') ?>" class="nav-link <?= service('uri')->getSegment(2) == 'riwayat' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Pengajuan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('mahasiswa/logbook') ?>" class="nav-link <?= service('uri')->getSegment(2) == 'logbook' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-book-open"></i>
                                <p>Logbook</p>
                            </a>
                        </li>

                        <!-- Header Aksi Sistem -->
                        <li class="nav-header">AKSI SISTEM</li>

                        <li class="nav-item">
                            <!-- Link logout mengarah ke route logout umum seperti kodemu sebelumnya -->
                            <a href="<?= base_url('logout') ?>" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                                <p class="text-danger">Logout</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?= $title ?? 'Dashboard Mahasiswa' ?></h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <!-- Content Dinamis -->
                    <?= $this->renderSection('content') ?>

                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer text-sm">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2026 Sistem Pengajuan Skripsi.</strong> All rights reserved.
        </footer>

    </div>

    <!-- REQUIRED SCRIPTS -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>

</body>

</html>