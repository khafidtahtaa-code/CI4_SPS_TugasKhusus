<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Skripsi - <?= esc($data['nama_mahasiswa']) ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">
    
    <style>
        .content-wrapper {
            background: linear-gradient(to bottom, #f4f6f9 0%, #e0eafc 100%) !important;
            min-height: 100vh;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: none;
        }
        .table th { background-color: #f8f9fa; width: 25%; color: #495057; }
    </style>
</head>

<body class="hold-transition layout-top-nav">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-white navbar-light shadow-sm">
            <div class="container">
                <a href="<?= base_url('publik/arsip') ?>" class="navbar-brand">
                    <i class="fas fa-arrow-left text-primary mr-2"></i>
                    <span class="font-weight-bold text-muted">Kembali ke Pencarian</span>
                </a>
            </div>
        </nav>

        <div class="content-wrapper">
            <section class="content pt-5 pb-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            
                            <div class="card card-primary card-outline">
                                <div class="card-header bg-white text-center py-4">
                                    <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                                    <h3 class="font-weight-bold mb-1"><?= esc($data['judul']) ?></h3>
                                    <span class="badge badge-success px-3 py-1 mt-2" style="font-size: 1rem;">Tahun Lulus: <?= $data['tahun_lulus'] ?></span>
                                </div>
                                
                                <div class="card-body p-4">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th>NPM</th>
                                                <td class="font-weight-bold"><?= esc($data['npm']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nama Penulis</th>
                                                <td><?= esc($data['nama_mahasiswa']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Angkatan</th>
                                                <td><?= esc($data['angkatan'] ?? '-') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Kategori Topik</th>
                                                <td><span class="badge badge-info"><?= esc($data['nama_topik'] ?: '-') ?></span></td>
                                            </tr>
                                            <tr>
                                                <th>Kata Kunci</th>
                                                <td><?= esc($data['kata_kunci'] ?: '-') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dosen Pembimbing 1</th>
                                                <td><?= esc($data['nama_pembimbing1'] ?: '-') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dosen Pembimbing 2</th>
                                                <td><?= esc($data['nama_pembimbing2'] ?: '-') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Abstrak</th>
                                                <td style="text-align: justify; line-height: 1.6;">
                                                    <em><?= nl2br(esc($data['abstrak'])) ?></em>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="text-center mt-5 mb-2">
                                        <?php if (isset($data['file_url']) && !empty($data['file_url'])): ?>
                                            <?php
                                            $file_link = trim($data['file_url']);
                                            if (strpos($file_link, 'drive.google.com') !== false) {
                                                if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $file_link, $matches) || preg_match('/id=([a-zA-Z0-9_-]+)/', $file_link, $matches)) {
                                                    $file_link = 'https://drive.google.com/file/d/' . $matches[1] . '/view';
                                                }
                                            } else if (!filter_var($file_link, FILTER_VALIDATE_URL)) {
                                                $file_link = base_url('public/uploads/' . $file_link);
                                            }
                                            ?>
                                            <button type="button" onclick="window.open('<?= $file_link ?>', '_blank', 'noopener,noreferrer');" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm">
                                                <i class="fas fa-file-pdf mr-2"></i> Baca Dokumen Skripsi
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-lg rounded-pill px-5" disabled>
                                                <i class="fas fa-ban mr-2"></i> Dokumen Tidak Tersedia
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</body>
</html>