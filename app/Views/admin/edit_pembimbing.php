<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card">
    <!-- 
    <div class="card-header">
        <h3 class="card-title">
            Edit Penetapan Pembimbing
        </h3>
    </div> -->

    <div class="card-body">

        <form onsubmit="return confirm('Simpan perubahan pembimbing?')"
            method="POST"
            action="<?= base_url('admin/update_pembimbing') ?>"
            enctype="multipart/form-data">

            <input
                type="hidden"
                name="id_pengajuan"
                value="<?= $data['id_pengajuan'] ?>">

            <div class="form-group">
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>NPM</label>
                            <input
                                type="text"
                                class="form-control"
                                value="<?= $data['npm'] ?>"
                                readonly>
                        </div>
                    </div>

                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label>Mahasiswa</label>
                            <input
                                type="text"
                                class="form-control"
                                value="<?= $data['npm'] ?>"
                                readonly>
                        </div>
                    </div> -->

                </div>
                <label>Judul Skripsi</label>

                <textarea
                    class="form-control"
                    rows="3"
                    readonly><?= $data['judul'] ?></textarea>

            </div>

            <div class="form-group">

                <label>Pembimbing 1</label>

                <select
                    name="pembimbing_1"
                    class="form-control">

                    <option value="">-- Pilih Pembimbing 1 --</option>

                    <?php foreach ($dosen as $d): ?>

                        <?php
                        $sisa =
                            ($d['kuota_bimbingan'] ?? 5)
                            -
                            ($d['total_bimbingan'] ?? 0);
                        ?>

                        <option
                            value="<?= $d['nidn'] ?>"
                            <?= ($pembimbing && $pembimbing['pembimbing_1'] == $d['nidn']) ? 'selected' : '' ?>>

                            <?= $d['nama'] ?>
                            (Sisa Kuota: <?= $sisa ?>)

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Pembimbing 2</label>

                <select
                    name="pembimbing_2"
                    class="form-control">

                    <option value="">-- Pilih Pembimbing 2 --</option>

                    <?php foreach ($dosen as $d): ?>

                        <?php
                        $sisa =
                            ($d['kuota_bimbingan'] ?? 5)
                            -
                            ($d['total_bimbingan'] ?? 0);
                        ?>

                        <option
                            value="<?= $d['nidn'] ?>"
                            <?= ($pembimbing && $pembimbing['pembimbing_2'] == $d['nidn']) ? 'selected' : '' ?>>

                            <?= $d['nama'] ?>
                            (Sisa Kuota: <?= $sisa ?>)

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Ganti File PDF SK</label>

                <input
                    type="file"
                    class="form-control"
                    name="file_pdf"
                    accept=".pdf">

                <small class="text-muted">
                    Kosongkan jika tidak ingin mengganti file.
                    <br>
                    File saat ini:
                    <strong>
                        <?php if (!empty($pembimbing['file_pdf'])): ?>

                            <!-- <a
                                href="<?= base_url('uploads/' . $pembimbing['file_pdf']) ?>"
                                target="_blank">

                                Lihat File Saat Ini

                            </a> -->

                            <a
                                href="<?= base_url('uploads/' . $pembimbing['file_pdf']) ?>"
                                target="_blank">

                                <i class="fas fa-file-pdf text-danger"></i>
                                <?= $pembimbing['file_pdf'] ?>

                            </a>

                        <?php else: ?>

                            <span class="text-muted">
                                Belum ada file
                            </span>

                        <?php endif; ?>
                    </strong>
                </small>

            </div>

            <hr>

            <button
                type="submit"
                class="btn btn-success btn-lg">

                <i class="fas fa-save"></i>
                Simpan Perubahan

            </button>

            <a
                href="<?= base_url('admin/riwayat_pembimbing') ?>"
                class="btn btn-secondary btn-lg">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </form>

    </div>

</div>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {

        let p1 = document.querySelector('[name="pembimbing_1"]').value;
        let p2 = document.querySelector('[name="pembimbing_2"]').value;

        if (p1 === '' && p2 === '') {
            alert('Pilih minimal 1 pembimbing!');
            e.preventDefault();
            return;
        }

        if (p1 !== '' && p2 !== '' && p1 === p2) {
            alert('Pembimbing 1 dan Pembimbing 2 tidak boleh sama!');
            e.preventDefault();
        }

    });
</script>

<?= $this->endSection() ?>