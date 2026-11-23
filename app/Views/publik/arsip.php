<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Arsip Skripsi - Publik</title>

    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    
    <style>
        /* Gradien abu-abu kebiruan halus untuk kesan bersih & lega */
        .content-wrapper {
            background: linear-gradient(to bottom, #f4f6f9 0%, #e0eafc 100%) !important;
            min-height: 100vh;
        }
        .card {
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: none;
        }
        .card-header {
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-white navbar-light shadow-sm">
            <div class="container">
                <a href="<?= base_url('/') ?>" class="navbar-brand">
                    <i class="fas fa-graduation-cap text-primary mr-2"></i>
                    <span class="font-weight-bold">SPS Arsip Skripsi</span>
                </a>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="<?= base_url('/') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login Portal
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content-wrapper">
            <section class="content pt-5 pb-5">
                <div class="container">
                    
                    <div class="text-center mb-5">
                        <h1 class="font-weight-bold" style="color: #2c3e50;">Database Arsip Skripsi</h1>
                        <p class="text-muted lead">Sistem Pengajuan Skripsi - Portal Publik</p>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET">
                                <div class="row align-items-center">
                                    <div class="col-md-5 mb-2 mb-md-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                                            </div>
                                            <input type="text" class="form-control border-left-0" name="keyword" value="<?= $keyword ?>" placeholder="Ketik judul, nama mahasiswa, atau abstrak...">
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <select class="form-control" name="tahun">
                                            <option value="">Semua Tahun</option>
                                            <?php foreach ($tahun_list as $t): ?>
                                                <option value="<?= $t['tahun_lulus'] ?>" <?= ($tahun == $t['tahun_lulus']) ? 'selected' : '' ?>><?= $t['tahun_lulus'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <select class="form-control" name="topik">
                                            <option value="">Semua Topik</option>
                                            <?php foreach ($topik_list as $t): ?>
                                                <option value="<?= $t['id_topik'] ?>" <?= ($topik == $t['id_topik']) ? 'selected' : '' ?>><?= $t['nama_topik'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100 mb-1"><i class="fas fa-search"></i> Cari</button>
                                        <a href="<?= base_url('publik/arsip') ?>" class="btn btn-light w-100 text-muted"><i class="fas fa-sync"></i> Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-primary px-3 py-2" style="font-size: 0.9rem;">
                            <i class="fas fa-database mr-1"></i> Total Arsip Ditemukan: <?= count($alumni) + count($lulus_tahun_ini) ?>
                        </span>
                    </div>

                    <div class="card card-outline card-success mb-4">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-success"><i class="fas fa-history mr-1"></i> Arsip Kelulusan Terdahulu</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-sm">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>NPM</th>
                                            <th>Mahasiswa</th>
                                            <th>Judul Skripsi</th>
                                            <th>Topik</th>
                                            <th>Tahun</th>
                                            <th width="8%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($alumni) > 0): ?>
                                            <?php $no = 1; foreach ($alumni as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="text-center font-weight-bold"><?= esc($row['npm']) ?></td>
                                                    <td><?= esc($row['nama_mahasiswa']) ?></td>
                                                    <td><?= esc($row['judul']) ?></td>
                                                    <td class="text-center"><span class="badge badge-info"><?= $row['nama_topik'] ?? '-' ?></span></td>
                                                    <td class="text-center"><span class="badge badge-success"><?= $row['tahun_lulus'] ?></span></td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('publik/detail/' . $row['id_arsip']) ?>" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fas fa-eye"></i> Detail</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted p-5">
                                                    <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>Belum ada data arsip yang sesuai.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-primary">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold text-primary"><i class="fas fa-award mr-1"></i> Mahasiswa Lulus Tahun Ini</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 text-sm">
                                    <thead class="bg-light text-center">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>NPM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Judul Skripsi</th>
                                            <th>Topik</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($lulus_tahun_ini) > 0): ?>
                                            <?php $no = 1; foreach ($lulus_tahun_ini as $row): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="text-center font-weight-bold"><?= esc($row['npm']) ?></td>
                                                    <td><?= esc($row['nama_mahasiswa']) ?></td>
                                                    <td><?= esc($row['judul']) ?></td>
                                                    <td class="text-center"><span class="badge badge-info"><?= esc($row['nama_topik'] ?? '-') ?></span></td>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('publik/detail/' . $row['id_arsip']) ?>" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fas fa-eye"></i> Detail</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted p-5">
                                                    <i class="fas fa-user-graduate fa-3x mb-3 text-light"></i><br>Belum ada mahasiswa yang lulus tahun ini.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>

</body>
</html>