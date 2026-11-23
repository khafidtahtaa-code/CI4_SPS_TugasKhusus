<?= $this->extend('layout/template_dosen') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $total ?></h3>
                <p>Total Pengajuan</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $pending ?></h3>
                <p>Menunggu Review</p>
            </div>
            <div class="icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $acc ?></h3>
                <p>ACC</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $tolak ?></h3>
                <p>Ditolak</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-3 col-6">

        <div class="small-box bg-primary">

            <div class="inner">

                <h3><?= $total_dibimbing ?></h3>

                <p>Mahasiswa Bimbingan</p>

            </div>

            <div class="icon">

                <i class="fas fa-user-graduate"></i>

            </div>

        </div>

    </div>

</div>
<!-- 
<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            Menu Dosen

        </h3>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-6 mb-3">

                <a
                    href="<?= base_url('dosen/review') ?>"
                    class="btn btn-primary btn-block">

                    <i class="fas fa-search mr-2"></i>

                    Review Pengajuan

                </a>

            </div>

            <div class="col-md-6 mb-3">

                <a
                    href="<?= base_url('dosen/riwayat_review') ?>"
                    class="btn btn-success btn-block">

                    <i class="fas fa-history mr-2"></i>

                    Riwayat Review

                </a>

            </div>

            <div class="col-md-6 mb-3">

                <a
                    href="<?= base_url('dosen/mahasiswa_bimbingan') ?>"
                    class="btn btn-info btn-block">

                    <i class="fas fa-user-graduate mr-2"></i>

                    Mahasiswa Bimbingan

                </a>

            </div>

            <div class="col-md-6 mb-3">

                <a
                    href="<?= base_url('dosen/logbook') ?>"
                    class="btn btn-warning btn-block">

                    <i class="fas fa-book mr-2"></i>

                    Logbook Digital

                </a>

            </div>

        </div>

    </div>


</div> -->

<?= $this->endSection() ?>