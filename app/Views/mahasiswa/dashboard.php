<?= $this->extend('layout/template_mahasiswa') ?>

<?= $this->section('content') ?>

<div class="row">

    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">

            <div class="inner">
                <h3><?= $total_pengajuan ?></h3>
                <p>Total Pengajuan Saya</p>
            </div>

            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>

        </div>

    </div>

</div>

<!-- <div class="card">

    <div class="card-header">
        <h3 class="card-title">Menu Mahasiswa</h3>
    </div>

    <div class="card-body">

        <div class="list-group">

            <a href="<?= base_url('mahasiswa/pengajuan') ?>" class="list-group-item list-group-item-action">
                <i class="fas fa-plus-circle mr-2"></i>
                Ajukan Judul Skripsi
            </a>

            <a href="<?= base_url('mahasiswa/status') ?>" class="list-group-item list-group-item-action">
                <i class="fas fa-search mr-2"></i>
                Tracking Status Pengajuan
            </a>

            <a href="<?= base_url('mahasiswa/riwayat') ?>" class="list-group-item list-group-item-action">
                <i class="fas fa-history mr-2"></i>
                Riwayat Pengajuan
            </a>

            <a href="<?= base_url('mahasiswa/logbook') ?>" class="list-group-item list-group-item-action">
                <i class="fas fa-book mr-2"></i>
                Logbook Bimbingan Digital
            </a>

        </div>

    </div>

</div> -->

<?= $this->endSection() ?>