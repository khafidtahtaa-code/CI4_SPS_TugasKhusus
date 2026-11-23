<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>


<div class="mb-3">
    <input
        type="text"
        id="searchPengajuan"
        class="form-control"
        placeholder="Cari mahasiswa, NPM, judul, topik...">
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="alert alert-info">
    Total Pengajuan Siap Ditetapkan :
    <b><?= count($data) ?></b>
</div>


<div class="table-responsive">
    <table id="tblPengajuan" class="table table-bordered table-hover">

        <thead>
            <tr>
                <th>NPM</th>
                <th>Mahasiswa</th>
                <th>Judul</th>
                <th>Status</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>

        <tbody>

            <?php if (count($data) > 0): ?>

                <?php foreach ($data as $row): ?>

                    <tr>

                        <td><?= $row['npm'] ?></td>

                        <td><?= $row['nama_mahasiswa'] ?></td>

                        <td>
                            <?= strlen($row['judul']) > 50
                                ? substr($row['judul'], 0, 50) . '...'
                                : $row['judul']; ?>
                        </td>

                        <td>

                            <?php if ($row['status_review'] == 'ACC'): ?>
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> ACC Dosen
                                </span>
                            <?php else: ?>
                                <span class="badge badge-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Ditolak Dosen
                                </span>
                            <?php endif; ?>

                        </td>

                        <td>

                            <button
                                type="button"
                                class="btn btn-info btn-sm"
                                data-toggle="modal"
                                data-target="#detail<?= $row['id_pengajuan'] ?>">

                                Detail

                            </button>

                        </td>

                    </tr>

                <?php endforeach; ?>


                <!-- <?php else: ?>

                <tr>
                    <td colspan="12" class="text-center text-muted">
                        Tidak ada pengajuan yang perlu ditetapkan
                    </td>
                </tr>



            <?php endif; ?> -->

        </tbody>

    </table>
</div>

<?php foreach ($data as $row): ?>
    <!-- Modal -->
    <div
        class="modal fade"
        id="detail<?= $row['id_pengajuan'] ?>"
        tabindex="-1">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Detail Pengajuan Skripsi

                    </h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="200">NPM</th>
                            <td><?= $row['npm'] ?></td>
                        </tr>

                        <tr>
                            <th>Mahasiswa</th>
                            <td><?= $row['nama_mahasiswa'] ?></td>
                        </tr>

                        <tr>
                            <th>Judul</th>
                            <td><?= $row['judul'] ?></td>
                        </tr>

                        <tr>
                            <th>Topik</th>
                            <td><?= $row['nama_topik'] ?></td>
                        </tr>

                        <tr>
                            <th>Calon Dosen</th>
                            <td><?= $row['nama_dosen'] ?></td>
                        </tr>

                        <tr>
                            <th>Feedback Dosen</th>
                            <td><?= $row['feedback'] ?: '-' ?></td>
                        </tr>

                    </table>

                    <hr>

                    <form
                        action="<?= base_url('admin/proses_penetapan') ?>"
                        method="POST"
                        enctype="multipart/form-data"
                        onsubmit="return validateForm('<?= $row['id_pengajuan'] ?>')">

                        <input
                            type="hidden"
                            name="id_pengajuan"
                            value="<?= $row['id_pengajuan'] ?>">

                        <div class="form-group">

                            <label>Pembimbing 1</label>

                            <select
                                class="form-control"
                                name="pembimbing_1"
                                id="p1_<?= $row['id_pengajuan'] ?>">

                                <option value="">
                                    -- Tidak Pilih --
                                </option>

                                <?php foreach ($dosen as $d): ?>
                                    <?php
                                    $sisa =
                                        ($d['kuota_bimbingan'] ?? 5)
                                        -
                                        ($d['total_bimbingan'] ?? 0);
                                    ?>

                                    <option value="<?= $d['nidn'] ?>">
                                        <?= $d['nama'] ?>
                                        (Sisa <?= $sisa ?>)
                                    </option>
                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Pembimbing 2</label>

                            <select
                                class="form-control"
                                name="pembimbing_2"
                                id="p2_<?= $row['id_pengajuan'] ?>">

                                <option value="">
                                    -- Tidak Pilih --
                                </option>

                                <?php foreach ($dosen as $d): ?>
                                    <?php
                                    $sisa =
                                        ($d['kuota_bimbingan'] ?? 5)
                                        -
                                        ($d['total_bimbingan'] ?? 0);
                                    ?>

                                    <option value="<?= $d['nidn'] ?>">
                                        <?= $d['nama'] ?>
                                        (Sisa <?= $sisa ?>)
                                    </option>
                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Upload SK PDF</label>

                            <input
                                type="file"
                                class="form-control"
                                name="file_pdf"
                                accept=".pdf"
                                required>

                        </div>

                        <div class="form-group">

                            <label>Feedback Admin</label>

                            <textarea
                                class="form-control"
                                rows="3"
                                name="feedback_admin"
                                placeholder="Contoh:
                                                Ditetapkan berdasarkan hasil rapat program studi.
                                                Override dilakukan karena topik masih relevan dengan bidang penelitian.">
                                            </textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success">

                            Tetapkan

                        </button>

                        <button
                            type="submit"
                            formaction="<?= base_url('admin/tolak_pengajuan') ?>"
                            formnovalidate
                            class="btn btn-danger">

                            Tolak Permanen

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
    <!-- modal -->
<?php endforeach; ?>



<script>
    function validateForm(id) {
        let p1 = document.getElementById('p1_' + id).value;
        let p2 = document.getElementById('p2_' + id).value;

        if (p1 === '' && p2 === '') {
            alert('Pilih minimal 1 pembimbing!');
            return false;
        }

        if (p1 !== '' && p2 !== '' && p1 === p2) {
            alert('Pembimbing 1 dan Pembimbing 2 tidak boleh sama!');
            return false;
        }

        return true;
    }
</script>

<script>
    document.getElementById('searchPengajuan')
        .addEventListener('keyup', function() {

            let keyword = this.value.toLowerCase();

            let rows =
                document.querySelectorAll('#tblPengajuan tbody tr');

            rows.forEach(function(row) {

                let text =
                    row.textContent.toLowerCase();

                row.style.display =
                    text.includes(keyword) ?
                    '' :
                    'none';
            });

        });
</script>

<?= $this->endSection() ?>