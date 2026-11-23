<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $total_mahasiswa ?></h3>
                    <p>Mahasiswa</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $total_dosen ?></h3>
                    <p>Dosen</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $total_topik ?></h3>
                    <p>Topik Skripsi</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $total_pengajuan ?></h3>
                    <p>Pengajuan</p>
                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>