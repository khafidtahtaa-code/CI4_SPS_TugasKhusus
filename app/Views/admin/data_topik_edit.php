<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Edit Topik Skripsi
        </h3>
    </div>

    <div class="card-body">

        <form method="POST" action="<?= base_url('admin/update_topik') ?>">

            <input
                type="hidden"
                name="id_topik"
                value="<?= $row['id_topik'] ?>">

            <div class="form-group">
                <label>ID Topik</label>

                <input
                    type="text"
                    class="form-control"
                    value="<?= $row['id_topik'] ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label>Nama Topik</label>

                <input
                    type="text"
                    class="form-control"
                    name="nama_topik"
                    value="<?= $row['nama_topik'] ?>"
                    required>
            </div>

            <hr>

            <button
                type="submit"
                class="btn btn-primary">

                <i class="fas fa-save"></i>
                Update Topik

            </button>

            <a
                href="<?= base_url('admin/topik') ?>"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </form>

    </div>
</div>

<?= $this->endSection() ?>