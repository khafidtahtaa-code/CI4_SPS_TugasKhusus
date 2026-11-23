<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $title ?? 'Sistem Pengajuan Skripsi' ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    
    <?= $this->renderSection('styles') ?>
    
    <style>
        @media (max-width: 767.98px) {
            /* Mencegah nama user di navbar kanan terlalu panjang dan merusak baris */
            .navbar-nav .nav-link {
                padding-right: 0.5rem;
                padding-left: 0.5rem;
                font-size: 0.9rem;
            }
            /* Menyembunyikan teks nama "Admin" di HP jika layar terlalu sempit agar tidak nabrak */
            .user-name-text {
                display: none;
            }
            /* Menyesuaikan jarak padding konten utama di HP agar lebih luas */
            .content-wrapper {
                padding: 10px 5px;
            }
            /* Mengatur judul halaman utama (H1) tidak terlalu besar di HP */
            .content-header h1 {
                font-size: 1.5rem;
                text-align: center;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed sidebar-closed sidebar-collapse">

    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">3 Notifikasi Baru</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file-alt mr-2"></i> Pengajuan Skripsi Baru
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                    </div>
                </li>

                <li class="nav-item">
                    <span class="nav-link">
                        <i class="fas fa-user-circle"></i>
                        <strong class="user-name-text"><?= session()->get('username') ?? 'Admin' ?></strong>
                    </span>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="<?= base_url('admin/dashboard') ?>" class="brand-link text-center d-flex align-items-center justify-content-center" style="height: calc(3.5rem + 1px); border-bottom: 1px solid #4f5962; padding: 0;">
                <span class="brand-text font-weight-light m-0">
                    <h4 class="m-0" style="font-size: 1.3rem; line-height: 1;"><strong>SPS</strong> <small class="text-muted">Admin</small></h4>
                </span>
            </a>

            <div class="sidebar">
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item">
                            <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= (current_url() == base_url('admin/dashboard')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">MENU UTAMA</li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/data_user') ?>" class="nav-link <?= (current_url() == base_url('admin/data_user')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/data_mahasiswa') ?>" class="nav-link <?= (current_url() == base_url('admin/data_mahasiswa')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Data Mahasiswa</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/data_dosen') ?>" class="nav-link <?= (current_url() == base_url('admin/data_dosen')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>Data Dosen</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/data_topik') ?>" class="nav-link <?= (current_url() == base_url('admin/data_topik')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-book-open"></i>
                                <p>Topik Skripsi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/data_pengajuan') ?>" class="nav-link <?= (current_url() == base_url('admin/data_pengajuan')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Data Review Dosen</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/penetapan_pembimbing') ?>" class="nav-link <?= (current_url() == base_url('admin/penetapan_pembimbing')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>Penetapan Pembimbing</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/riwayat_pembimbing') ?>" class="nav-link <?= (current_url() == base_url('admin/riwayat_pembimbing')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Penetapan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/batal_penetapan') ?>" class="nav-link <?= (current_url() == base_url('admin/batal_penetapan')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-user-times"></i>
                                <p>Batal Penetapan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/statistik') ?>" class="nav-link <?= (current_url() == base_url('admin/statistik')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Laporan & Statistik</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/kelola_sidang') ?>" class="nav-link <?= (current_url() == base_url('admin/kelola_sidang')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-gavel"></i>
                                <p>Kelola Sidang</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/arsip_skripsi') ?>" class="nav-link <?= (current_url() == base_url('admin/arsip_skripsi')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-archive"></i>
                                <p>Database Arsip Skripsi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/import_excel') ?>" class="nav-link <?= (current_url() == base_url('admin/import_excel')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-excel"></i>
                                <p>Import Data Excel</p>
                            </a>
                        </li>

                        <li class="nav-header">AKSI SISTEM</li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/logout') ?>" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                                <p class="text-danger">Logout</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-12 text-md-left">
                            <h1><?= $title ?? 'Halaman Admin' ?></h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?= $this->renderSection('content') ?>

                </div>
            </section>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; <?= date('Y') ?> Sistem Pengajuan Skripsi.</strong> All rights reserved.
        </footer>

    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>

</body>
</html>