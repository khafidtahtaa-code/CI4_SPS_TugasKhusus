<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Edit Data Mahasiswa
        </h3>
    </div>

    <form method="POST"
        action="<?= base_url('admin/update_mahasiswa') ?>">

        <div class="card-body">

            <input
                type="hidden"
                name="npm"
                value="<?= $row['npm'] ?>">

            <div class="form-group">

                <label>NPM</label>

                <input
                    type="text"
                    class="form-control"
                    value="<?= $row['npm'] ?>"
                    readonly>

            </div>

            <div class="form-group">

                <label>Nama Mahasiswa</label>

                <input
                    type="text"
                    class="form-control"
                    name="nama"
                    value="<?= $row['nama'] ?>"
                    required>

            </div>

            <div class="form-group">

                <label>Angkatan</label>

                <input
                    type="number"
                    class="form-control"
                    name="angkatan"
                    value="<?= $row['angkatan'] ?>"
                    required>

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary">

                <i class="fas fa-save"></i>
                Update Mahasiswa

            </button>

            <a href="<?= base_url('admin/data_mahasiswa') ?>"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>