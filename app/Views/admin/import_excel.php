<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('pesan_gagal')): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> Terjadi Kesalahan Import:</h5>
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('pesan_gagal') as $p): ?>
                <li><?= $p ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Import Data Mahasiswa</h3>
            </div>
            <div class="card-body">
                <p class="text-muted text-sm">Format: <b>NPM | Nama | Angkatan</b></p>
                <a href="<?= base_url('admin/download_template_mahasiswa') ?>" class="btn btn-outline-info btn-sm mb-3">
                    <i class="fas fa-download"></i> Download Template
                </a>
                <form method="POST" action="<?= base_url('admin/proses_import_mahasiswa') ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="file_excel" class="form-control-file" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-upload"></i> Upload & Import</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Import Data Dosen</h3>
            </div>
            <div class="card-body">
                <p class="text-muted text-sm">Format: <b>NIDN | Nama | Bidang | Kuota</b></p>
                <a href="<?= base_url('admin/download_template_dosen') ?>" class="btn btn-outline-primary btn-sm mb-3">
                    <i class="fas fa-download"></i> Download Template
                </a>
                <form method="POST" action="<?= base_url('admin/proses_import_dosen') ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="file_excel" class="form-control-file" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-upload"></i> Upload & Import</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Bulk Upload Arsip Skripsi</h3>
            </div>
            <div class="card-body">
                <p class="text-muted text-sm">Format Excel/CSV Lengkap sesuai Database.</p>
                <a href="<?= base_url('admin/download_template_arsip') ?>" class="btn btn-outline-warning btn-sm mb-3">
                    <i class="fas fa-download"></i> Download Template
                </a>
                <form method="POST" action="<?= base_url('admin/proses_import_arsip') ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="file_excel" class="form-control-file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fas fa-upload"></i> Upload & Import</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <h5><i class="icon fas fa-info"></i> Informasi Penting!</h5>
    Setelah import mahasiswa/dosen, data otomatis juga terisi ke <b>Manajemen User</b>. Pengguna baru perlu membuat password sendiri di halaman pengaturan profil/password menggunakan kombinasi wajib <b>Huruf dan Angka</b>.
</div>

<?= $this->endSection() ?>