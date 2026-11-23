<?= $this->extend('layout/template_mahasiswa') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle mr-1"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <i class="fas fa-times-circle mr-1"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if ($pengajuan && $pengajuan['status'] == 'Ditetapkan'): ?>

    <div class="card">

        <div class="card-header bg-primary">

            <h3 class="card-title text-white">
                <i class="fas fa-book mr-1"></i>
                Tambah Catatan Bimbingan
            </h3>

        </div>

        <form
            action="<?= base_url('mahasiswa/simpan_logbook') ?>"
            method="POST">

            <?= csrf_field() ?>

            <input
                type="hidden"
                name="id_pengajuan"
                value="<?= $pengajuan['id_pengajuan'] ?>">

            <div class="card-body">

                <div class="form-group">

                    <label>Dosen Pembimbing</label>

                    <select
                        name="nidn_dosen"
                        class="form-control"
                        required>

                        <option value="<?= $pengajuan['pembimbing_1'] ?>">
                            Pembimbing 1 - <?= $pengajuan['nama_pembimbing1'] ?>
                        </option>

                        <?php if (!empty($pengajuan['pembimbing_2'])): ?>

                            <option value="<?= $pengajuan['pembimbing_2'] ?>">
                                Pembimbing 2 - <?= $pengajuan['nama_pembimbing2'] ?>
                            </option>

                        <?php endif; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>Tanggal Bimbingan</label>

                    <input
                        type="date"
                        name="tanggal_bimbingan"
                        class="form-control"
                        required>

                </div>

                <div class="form-group">

                    <label>Materi / Bahasan</label>

                    <textarea
                        name="materi_bimbingan"
                        rows="5"
                        class="form-control"
                        placeholder="Contoh: Revisi Bab 1, perbaikan metodologi, dll..."
                        required></textarea>

                </div>

                <div class="form-group">

                    <label>Link Draft / File Google Drive</label>

                    <input
                        type="url"
                        name="file_mahasiswa_url"
                        class="form-control"
                        placeholder="https://drive.google.com/...">

                    <small class="text-muted">
                        Pastikan file Google Drive sudah diatur menjadi
                        <b>Anyone with the link can view.</b>
                    </small>

                </div>

            </div>

            <div class="card-footer">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fas fa-paper-plane mr-1"></i>
                    Kirim Logbook

                </button>

            </div>

        </form>

    </div>

    <div class="mb-4"></div>


    <!-- ========================= -->
    <!-- PENDAFTARAN SIDANG -->
    <!-- ========================= -->

    <div class="card">

        <div class="card-header bg-info">

            <h3 class="card-title text-white">

                <i class="fas fa-graduation-cap mr-1"></i>
                Pendaftaran Sidang Skripsi

            </h3>

        </div>

        <div class="card-body">

            <?php if ($jumlah_disetujui > 0): ?>

                <div class="alert alert-success">

                    <i class="fas fa-check-circle"></i>

                    Anda sudah memiliki minimal
                    <b>1 Logbook Disetujui.</b>

                    Form pendaftaran sidang sudah terbuka.

                </div>

            <?php else: ?>

                <div class="alert alert-warning">

                    <i class="fas fa-exclamation-triangle"></i>

                    Anda harus memiliki minimal
                    <b>1 Logbook Disetujui</b>
                    oleh dosen sebelum dapat mendaftar sidang.

                </div>

            <?php endif; ?>

            <form
                action="<?= base_url('mahasiswa/proses_daftar_sidang') ?>"
                method="POST">

                <?= csrf_field() ?>

                <input
                    type="hidden"
                    name="id_pengajuan"
                    value="<?= $pengajuan['id_pengajuan'] ?>">

                <div class="form-group">

                    <label>Link Google Drive Skripsi Final</label>

                    <input
                        type="url"
                        name="link_draf_skripsi"
                        class="form-control"
                        placeholder="https://drive.google.com/..."
                        <?= $jumlah_disetujui == 0 ? 'disabled' : '' ?>
                        required>

                    <small class="text-muted">

                        Pastikan file dapat diakses oleh dosen.

                    </small>

                </div>

        </div>

        <div class="card-footer">

            <?php if ($jumlah_disetujui == 0): ?>

                <button
                    type="submit"
                    class="btn btn-secondary"
                    disabled>

                    <i class="fas fa-lock"></i>

                    Menunggu Persetujuan Dosen

                </button>

            <?php else: ?>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="fas fa-paper-plane"></i>

                    Kirim Pendaftaran Sidang

                </button>

            <?php endif; ?>

        </div>

        </form>

    </div>

<?php elseif ($pengajuan && in_array($pengajuan['status'], ['Menunggu Sidang', 'Lulus'])): ?>

    <div class="alert alert-info">

        <h5>

            <i class="fas fa-lock"></i>

            Logbook Ditutup

        </h5>

        Anda sudah mendaftarkan diri untuk sidang skripsi.

        <br>

        Status saat ini :

        <span class="badge badge-primary">

            <?= $pengajuan['status'] ?>

        </span>

        <br><br>

        Pengisian logbook baru sudah tidak diperbolehkan.

    </div>

<?php else: ?>

    <div class="alert alert-warning">

        <h5>

            <i class="fas fa-exclamation-circle"></i>

            Logbook Belum Tersedia

        </h5>

        Fitur Logbook hanya dapat digunakan apabila status skripsi sudah

        <b>Ditetapkan</b>.

    </div>

<?php endif; ?>

<div class="mb-4"></div>



<!-- ========================================= -->
<!-- RIWAYAT LOGBOOK -->
<!-- ========================================= -->

<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-history"></i>

            Riwayat Logbook Saya

        </h3>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="thead-light">

                    <tr>

                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="15%">Dosen</th>
                        <th width="25%">Materi</th>
                        <th width="25%">Feedback Dosen</th>
                        <th width="10%">File Review</th>
                        <th width="10%">Status</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($logbook)): ?>

                        <tr>

                            <td colspan="7" class="text-center text-muted">

                                Belum ada riwayat bimbingan.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php

                        $lastIdPengajuan = "";
                        $no = 1;

                        foreach ($logbook as $row):

                            if ($lastIdPengajuan != $row['id_pengajuan']):

                        ?>

                                <tr class="table-secondary">

                                    <td colspan="7">

                                        <strong>

                                            <i class="fas fa-book"></i>

                                            <?= esc($row['judul'] ?? '-') ?>

                                        </strong>

                                        <br>

                                        <?php if ($row['status_pengajuan'] == 'Dibatalkan'): ?>

                                            <span class="badge badge-danger">

                                                Dibatalkan

                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-success">

                                                <?= esc($row['status_pengajuan']) ?>

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php

                                $lastIdPengajuan = $row['id_pengajuan'];

                            endif;

                            ?>

                            <tr>

                                <td class="text-center">

                                    <?= $no++ ?>

                                </td>

                                <td>

                                    <?= date('d-m-Y', strtotime($row['tanggal_bimbingan'])) ?>

                                </td>

                                <td>

                                    <?= esc($row['nama_dosen']) ?>

                                </td>

                                <td>

                                    <?= nl2br(esc($row['materi_bimbingan'])) ?>

                                </td>

                                <td>

                                    <?php if ($row['catatan_dosen']): ?>

                                        <?= nl2br(esc($row['catatan_dosen'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">

                                            Belum direview

                                        </span>

                                    <?php endif; ?>

                                    <?php if (!empty($row['waktu_review'])): ?>

                                        <br><br>

                                        <small class="text-primary">

                                            <i class="far fa-clock"></i>

                                            <?= date('d-m-Y H:i', strtotime($row['waktu_review'])) ?>

                                        </small>

                                    <?php endif; ?>

                                </td>

                                <td class="text-center">

                                    <?php if (!empty($row['file_review_url'])): ?>

                                        <a
                                            href="<?= esc($row['file_review_url']) ?>"
                                            target="_blank"
                                            class="btn btn-info btn-sm">

                                            <i class="fas fa-link"></i>

                                            Buka

                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted">

                                            -

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td class="text-center">

                                    <?php

                                    if ($row['status_validasi'] == 'Disetujui'):

                                    ?>

                                        <span class="badge badge-success">

                                            Disetujui

                                        </span>

                                    <?php

                                    elseif ($row['status_validasi'] == 'Revisi'):

                                    ?>

                                        <span class="badge badge-danger">

                                            Revisi

                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-warning">

                                            Menunggu

                                        </span>

                                    <?php endif; ?>

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