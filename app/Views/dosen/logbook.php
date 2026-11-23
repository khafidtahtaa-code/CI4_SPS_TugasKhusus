<?= $this->extend('layout/template_dosen') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fas fa-check-circle"></i>

        <?= session()->getFlashdata('success') ?>

        <button type="button"
            class="close"
            data-dismiss="alert">

            <span>&times;</span>

        </button>

    </div>

<?php endif; ?>

<div class="card">

    <div class="card-header bg-primary">

        <h3 class="card-title text-white">

            Validasi Logbook Bimbingan Mahasiswa

        </h3>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover table-striped">

                <thead class="text-center">

                    <tr>

                        <th>Tanggal</th>
                        <th>Materi</th>
                        <th>Draft</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Link Review</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($logbook)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada logbook yang dikirimkan mahasiswa.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $lastStudent = ""; // Siapkan variabel penanda awal
                        foreach ($logbook as $row):
                            $formId = "form_log_" . $row['id_logbook'];

                            // LOGIKA GROUPING: Jika nama mahasiswa berbeda dari baris sebelumnya, buat pembatas
                            if ($lastStudent != $row['nama_mahasiswa']):
                        ?>
                                <tr class="table-primary">

                                    <td colspan="7">

                                        <strong>

                                            <i class="fas fa-user-graduate mr-1"></i>

                                            <?= esc($row['nama_mahasiswa']) ?>

                                        </strong>

                                        <small class="d-block">

                                            NPM :
                                            <?= esc($row['npm']) ?>

                                        </small>

                                    </td>

                                </tr>
                            <?php
                                $lastStudent = $row['nama_mahasiswa']; // Update penanda nama mahasiswa
                            endif;
                            ?>

                            <form id="<?= $formId ?>" action="<?= base_url('dosen/validasi_logbook') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_logbook" value="<?= $row['id_logbook'] ?>">
                            </form>

                            <tr>
                                <td class="align-top">
                                    <?= date('d-m-Y', strtotime($row['tanggal_bimbingan'])) ?></td>

                                <td class="align-top">
                                    <?= nl2br(esc($row['materi_bimbingan'])) ?></td>

                                <td class="align-top">
                                    <?php if (!empty($row['file_mahasiswa_url'])): ?>
                                        <a href="<?= esc($row['file_mahasiswa_url']) ?>"
                                            class="btn btn-sm btn-info"
                                            target="_blank">

                                            <i class="fas fa-folder-open"></i>

                                            Buka Draft

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="align-top">
                                    <select
                                        class="form-control form-control-sm"
                                        form="<?= $formId ?>"
                                        name="status_validasi"
                                        required>
                                        <option value="Menunggu" <?= $row['status_validasi'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                        <option value="Disetujui" <?= $row['status_validasi'] == 'Disetujui' ? 'selected' : '' ?>>ACC</option>
                                        <option value="Revisi" <?= $row['status_validasi'] == 'Revisi' ? 'selected' : '' ?>>Revisi</option>
                                    </select>
                                </td>

                                <td class="align-top">
                                    <textarea
                                        class="form-control"
                                        form="<?= $formId ?>"
                                        name="catatan_dosen"
                                        rows="4"
                                        placeholder="Tulis catatan..."><?= esc($row['catatan_dosen']) ?></textarea>
                                </td>

                                <td class="align-top">
                                    <input

                                        type="url"

                                        class="form-control" form="<?= $formId ?>" name="file_review_url"
                                        placeholder="https://drive.google.com/..." value="<?= esc($row['file_review_url'] ?? '') ?>">
                                </td>

                                <td class="align-top">
                                    <button
                                        form="<?= $formId ?>"
                                        type="submit"
                                        class="btn btn-success btn-sm btn-block">

                                        <i class="fas fa-save"></i>

                                        Simpan

                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>


                </tbody>

            </table>

        </div>

    </div>

</div>



<?= $this->endSection() ?>