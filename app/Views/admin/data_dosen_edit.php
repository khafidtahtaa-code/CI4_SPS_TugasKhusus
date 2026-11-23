<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Edit Data Dosen</h3>
    </div>

    <form method="POST" action="<?= base_url('admin/update_dosen') ?>">

        <div class="card-body">

            <input type="hidden"
                name="nidn"
                value="<?= $row['nidn'] ?>">

            <div class="form-group">
                <label>NIDN</label>
                <input type="text"
                    class="form-control"
                    value="<?= $row['nidn'] ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label>Nama Dosen</label>
                <input type="text"
                    name="nama"
                    class="form-control"
                    value="<?= $row['nama'] ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Bidang Keahlian</label>

                <select name="bidang_keahlian"
                    class="form-control"
                    required>

                    <option value="">
                        -- Pilih Topik --
                    </option>

                    <?php foreach ($list_topik as $t): ?>

                        <option value="<?= $t['nama_topik'] ?>"
                            <?= ($row['bidang_keahlian'] == $t['nama_topik']) ? 'selected' : '' ?>>

                            <?= $t['nama_topik'] ?>

                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label>Kuota Bimbingan</label>

                <input type="number"
                    name="kuota_bimbingan"
                    class="form-control"
                    value="<?= $row['kuota_bimbingan'] ?>"
                    min="1"
                    required>
            </div>

            <div class="form-group">
                <label>Total Bimbingan Saat Ini</label>

                <input type="number"
                    class="form-control"
                    value="<?= $row['total_bimbingan'] ?>"
                    readonly>
            </div>

            <div class="alert alert-info">
                <strong>Catatan:</strong><br>
                Total Bimbingan dihitung otomatis oleh sistem dan tidak dapat diubah secara manual.
            </div>

        </div>

        <div class="card-footer">

            <button type="submit"
                class="btn btn-primary">

                <i class="fas fa-save"></i>
                Update Dosen

            </button>

            <a href="<?= base_url('admin/data_dosen') ?>"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>