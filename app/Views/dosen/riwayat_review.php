<?= $this->extend('layout/template_dosen') ?>
<?= $this->section('content') ?>

<div class="card">

    <div class="card-header bg-success">

        <h3 class="card-title text-white">

            Data ACC (Telah Anda Setujui)

        </h3>

    </div>

    <div class="card-body">

        <?php if (count($data_acc) > 0): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>Mahasiswa</th>
                            <th>Angkatan</th>
                            <th>Judul</th>
                            <th>Abstrak</th>
                            <th>Topik</th>
                            <th>Status Review</th>
                            <th>Feedback Anda</th>
                            <th>Tanggal Review</th>
                            <th>Status Admin</th>
                            <th>Feedback Admin</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($data_acc as $row): ?>

                            <tr>

                                <td><?= $row['nama_mahasiswa'] ?></td>

                                <td><?= $row['angkatan'] ?></td>

                                <td><?= $row['judul'] ?></td>

                                <td><?= $row['abstrak'] ?></td>

                                <td><?= $row['nama_topik'] ?></td>

                                <td>

                                    <span class="badge badge-success">

                                        <?= $row['status_review'] ?>

                                    </span>

                                </td>

                                <td>

                                    <?= $row['feedback'] ?: '-' ?>

                                </td>

                                <td>

                                    <?= $row['tgl_review'] ?>

                                </td>


                                <td align="center">
                                    <?php if ($row['status'] == 'Ditolak Permanen' || $row['kondisi_data'] == 'Ditolak Permanen'): ?>
                                        <span class="badge badge-danger">
                                            DITOLAK PERMANEN
                                        </span>
                                    <?php elseif ($row['kondisi_data'] == 'Dibatalkan'): ?>
                                        <span class="badge badge-secondary">
                                            DIBATALKAN ADMIN
                                        </span>
                                    <?php elseif (in_array($row['status'], ['Ditetapkan', 'Lulus', 'Menunggu Sidang'])): ?>
                                        <span class="badge badge-success">
                                            DITETAPKAN
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">
                                            MENUNGGU
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>

                                    <?php if ($row['kondisi_data'] == 'Dibatalkan'): ?>

                                        <span class="text-muted">

                                            -

                                        </span>

                                    <?php elseif ($row['status'] == 'Ditolak Permanen' || $row['kondisi_data'] == 'Ditolak Permanen'): ?>

                                        <span class="text-danger font-weight-bold">

                                            <?= !empty($row['feedback_admin']) ? esc($row['feedback_admin']) : 'Tidak ada keterangan' ?>

                                        </span>

                                    <?php elseif (in_array($row['status'], ['Ditetapkan', 'Lulus', 'Menunggu Sidang'])): ?>

                                        <span class="text-muted">

                                            Tidak ada keterangan

                                        </span>

                                    <?php else: ?>

                                        <span class="text-warning">

                                            Menunggu...

                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="text-center py-4 text-muted">

                <i class="fas fa-info-circle fa-2x mb-2 text-warning"></i>
                
                <p class="mb-0 font-weight-bold">

                Belum ada pengajuan yang di-ACC.

            </div>

        <?php endif; ?>

    </div>

</div>

<div class="mb-4"></div>



<div class="card">

    <div class="card-header bg-danger">

        <h3 class="card-title text-white">

            Data Ditolak (Telah Anda Tolak)

        </h3>

    </div>

    <div class="card-body">

        <?php if (count($data_tolak) > 0): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>Mahasiswa</th>
                            <th>Angkatan</th>
                            <th>Judul</th>
                            <th>Abstrak</th>
                            <th>Topik</th>
                            <th>Status Review</th>
                            <th>Feedback Anda</th>
                            <th>Tanggal Review</th>
                            <th>Status Admin</th>
                            <th>Feedback Admin</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($data_tolak as $row): ?>

                            <tr>

                                <td><?= $row['nama_mahasiswa'] ?></td>

                                <td><?= $row['angkatan'] ?></td>

                                <td><?= $row['judul'] ?></td>

                                <td><?= $row['abstrak'] ?></td>

                                <td><?= $row['nama_topik'] ?></td>

                                <td>
                                    <span class="badge badge-danger">
                                        <?= $row['status_review'] ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $row['feedback'] ?: '-' ?>
                                </td>

                                <td>
                                    <?= $row['tgl_review'] ?>
                                </td>

                                <td align="center">

                                    <?php if (in_array($row['status'], ['Ditetapkan', 'Lulus', 'Menunggu Sidang'])): ?>

                                        <span class="badge badge-warning">
                                            DI-OVERRIDE ADMIN
                                        </span>
                                        <br>
                                        <small>(Anda tetap menjadi pembimbing)</small>

                                    <?php elseif ($row['status'] == 'Ditolak Permanen' || $row['kondisi_data'] == 'Ditolak Permanen'): ?>

                                        <span class="badge badge-success">
                                            TETAP DITOLAK
                                        </span>

                                    <?php elseif ($row['kondisi_data'] == 'Dibatalkan'): ?>

                                        <span class="badge badge-success">
                                            TETAP DITOLAK
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-warning">
                                            MENUNGGU
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (in_array($row['status'], ['Ditetapkan', 'Lulus', 'Menunggu Sidang'])): ?>

                                        <span class="text-warning font-weight-bold">
                                            <?= !empty($row['feedback_admin']) ? esc($row['feedback_admin']) : 'Tidak ada keterangan' ?>
                                        </span>

                                    <?php elseif ($row['kondisi_data'] == 'Dibatalkan'): ?>

                                        <span class="text-muted">
                                            -
                                        </span>

                                    <?php elseif ($row['status'] == 'Ditolak Permanen' || $row['kondisi_data'] == 'Ditolak Permanen'): ?>

                                        <span class="text-danger font-weight-bold">
                                            <?= !empty($row['feedback_admin']) ? esc($row['feedback_admin']) : 'Tidak ada keterangan' ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="text-warning">
                                            Menunggu...
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="text-center py-4 text-muted">

                <i class="fas fa-info-circle fa-2x mb-2 text-warning"></i>

                <p class="mb-0 font-weight-bold">

                Belum ada pengajuan yang ditolak.

            </div>

        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>