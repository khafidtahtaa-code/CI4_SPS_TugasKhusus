<?= $this->extend('layout/template_dosen') ?>
<?= $this->section('content') ?>

<div class="card">

    <div class="card-header bg-primary">

        <h3 class="card-title text-white">

            Filter Mahasiswa Bimbingan

        </h3>

    </div>

    <div class="card-body">

        <p class="text-muted mb-3">
            Daftar mahasiswa yang sedang atau telah selesai Anda bimbing.
        </p>

        <form method="GET" action="">

            <div class="row">

                <div class="col-md-4">

                    <label>Status Mahasiswa</label>

                    <select
                        name="filter_status"
                        class="form-control"
                        onchange="this.form.submit()">

                        <option value="">-- Semua Status --</option>

                        <option value="Proses"
                            <?= (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Proses') ? 'selected' : '' ?>>
                            Proses
                        </option>

                        <option value="Lulus"
                            <?= (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Lulus') ? 'selected' : '' ?>>
                            Lulus
                        </option>

                    </select>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="card">

    <div class="card-header bg-success">

        <h3 class="card-title text-white">

            Mahasiswa Bimbingan Aktif

        </h3>

    </div>

    <div class="card-body">

        <?php if (!empty($bimbingan_aktif)): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover table-striped">

                    <thead class="text-center">

                        <tr>

                            <th>No</th>
                            <th>NPM</th>
                            <th>Mahasiswa</th>
                            <th>Angkatan</th>
                            <th>Judul</th>
                            <th>Topik</th>
                            <th>Posisi</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Tgl Penetapan</th>
                            <th>Berkas</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php $no = 1;
                        foreach ($bimbingan_aktif as $row): ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td><?= $row['npm'] ?? '-' ?></td>

                                <td><?= $row['nama_mahasiswa'] ?? '-' ?></td>

                                <td><?= $row['angkatan'] ?></td>

                                <td><?= $row['judul'] ?? '-' ?></td>

                                <td><?= $row['nama_topik'] ?? '-' ?></td>

                                <td>

                                    <?php if (($row['pembimbing_1'] ?? '') == $nidn): ?>

                                        <span class="badge badge-success">

                                            Pembimbing 1

                                        </span>

                                    <?php elseif (($row['pembimbing_2'] ?? '') == $nidn): ?>

                                        <span class="badge badge-info">

                                            Pembimbing 2

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td><?= $row['nama_pembimbing1'] ?? '-' ?></td>

                                <td><?= $row['nama_pembimbing2'] ?? '-' ?></td>

                                <td>

                                    <?= !empty($row['tgl_penetapan']) ? date('d-m-Y', strtotime($row['tgl_penetapan'])) : '-' ?>

                                </td>

                                <td>

                                    <?php if (!empty($row['link_draf_skripsi'])): ?>

                                        <a
                                            href="<?= $row['link_draf_skripsi'] ?>"
                                            target="_blank"
                                            class="btn btn-sm btn-primary">

                                            <i class="fas fa-folder-open"></i>

                                            Drive

                                        </a>

                                    <?php else: ?>

                                        <span class="text-muted">-</span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (($row['status'] ?? '') == 'Lulus'): ?>

                                        <span class="badge badge-success">

                                            LULUS

                                        </span>

                                    <?php elseif (($row['status_review'] ?? '') == 'Ditolak' && ($row['status'] ?? '') == 'Ditetapkan'): ?>

                                        <span class="badge badge-purple">

                                            DITETAPKAN ADMIN

                                        </span>

                                    <?php elseif (($row['status'] ?? '') == 'Menunggu Sidang'): ?>

                                        <span class="badge badge-info">

                                            MENUNGGU SIDANG

                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-warning">

                                            PROSES

                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                <strong>Total Mahasiswa Aktif :</strong>

                <span class="badge badge-success">

                    <?= count($bimbingan_aktif) ?>

                </span>

            </div>

        <?php else: ?>

            <div class="alert alert-white mb-0">

                <i class="fas fa-info-circle"></i>

                Belum ada mahasiswa bimbingan aktif.

            </div>

        <?php endif; ?>

    </div>

</div>


<div class="card">

    <div class="card-header bg-danger">

        <h3 class="card-title text-white">

            Riwayat Penetapan yang Dibatalkan Admin

        </h3>

    </div>

    <div class="card-body">

        <p class="text-muted">
            Daftar mahasiswa yang sempat ditetapkan kepada Anda namun telah dibatalkan oleh Admin.
        </p>

        <?php if (!empty($bimbingan_batal)): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover table-striped">

                    <thead class="text-center">

                        <tr>

                            <th>No</th>
                            <th>NPM</th>
                            <th>Mahasiswa</th>
                            <th>Judul Skripsi</th>
                            <th>Posisi</th>
                            <th>Feedback Admin</th>
                            <th>Tanggal Dibatalkan</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php $no = 1;
                        foreach ($bimbingan_batal as $row): ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td><?= $row['npm'] ?? '-' ?></td>

                                <td><?= $row['nama_mahasiswa'] ?? '-' ?></td>

                                <td><?= $row['judul'] ?? '-' ?></td>

                                <td>

                                    <?php if (($row['pembimbing_1'] ?? '') == $nidn): ?>

                                        <span class="badge badge-success">

                                            Pembimbing 1

                                        </span>

                                    <?php elseif (($row['pembimbing_2'] ?? '') == $nidn): ?>

                                        <span class="badge badge-info">

                                            Pembimbing 2

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= !empty($row['feedback_admin'])
                                        ? esc($row['feedback_admin'])
                                        : '<span class="text-muted">-</span>' ?>

                                </td>

                                <td>

                                    <?= !empty($row['tgl_dibatalkan_ditolak'])
                                        ? date('d-m-Y H:i', strtotime($row['tgl_dibatalkan_ditolak']))
                                        : '-' ?>

                                </td>

                                <td class="text-center">

                                    <span class="badge badge-danger">

                                        DIBATALKAN ADMIN

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="alert alert-white mb-0">

                <i class="fas fa-info-circle"></i>

                Tidak ada riwayat bimbingan yang dibatalkan.

            </div>

        <?php endif; ?>

    </div>

</div>


<?= $this->endSection() ?>