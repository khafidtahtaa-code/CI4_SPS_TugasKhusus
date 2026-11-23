<?= $this->extend('layout/template_dosen') ?>
<?= $this->section('content') ?>


<!-- =========================
    INFORMASI KUOTA
========================== -->

<div class="card">

    <div class="card-header bg-info">

        <h3 class="card-title text-white">

            Informasi Kuota Bimbingan

        </h3>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>

                        <th>Kuota Bimbingan</th>
                        <th>Total Bimbingan</th>
                        <th>Sisa Kuota</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td><?= $dosen['kuota_bimbingan'] ?></td>

                        <td><?= $total_bimbingan ?></td>

                        <td><?= $sisa_kuota ?></td>

                        <td>

                            <?php if ($sisa_kuota <= 0): ?>

                                <span class="badge badge-danger">

                                    Penuh

                                </span>

                            <?php else: ?>

                                <span class="badge badge-success">

                                    Tersedia

                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- =========================
    PENGAJUAN PENDING
========================== -->

<div class="card">

    <div class="card-header bg-warning">

        <h3 class="card-title">

            Pengajuan Pending

        </h3>

    </div>

    <div class="card-body">

        <?php if (count($data_pending) > 0): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>Mahasiswa</th>
                            <th>Angkatan</th>
                            <th>Judul</th>
                            <th>Abstrak</th>
                            <th>Topik</th>
                            <th>Status</th>
                            <th>Feedback</th>
                            <th width="180">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($data_pending as $row): ?>

                            <tr>

                                <td><?= $row['nama_mahasiswa'] ?></td>

                                <td><?= $row['angkatan'] ?></td>

                                <td><?= $row['judul'] ?></td>

                                <td><?= $row['abstrak'] ?></td>

                                <td><?= $row['nama_topik'] ?></td>

                                <td>

                                    <span class="badge badge-warning">

                                        Pending

                                    </span>

                                </td>

                                <form action="<?= base_url('dosen/proses_review') ?>" method="POST">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $row['id_pengajuan'] ?>">

                                    <td>

                                        <textarea
                                            name="feedback"
                                            class="form-control"
                                            rows="5"
                                            placeholder="Masukkan feedback / catatan revisi"></textarea>

                                    </td>

                                    <td>

                                        <?php if ($sisa_kuota > 0): ?>

                                            <button
                                                type="submit"
                                                name="status"
                                                value="ACC"
                                                class="btn btn-success btn-sm btn-block">

                                                <i class="fas fa-check"></i>

                                                ACC

                                            </button>

                                        <?php else: ?>

                                            <button
                                                class="btn btn-secondary btn-sm btn-block"
                                                disabled>

                                                <i class="fas fa-ban"></i>

                                                Kuota Penuh

                                            </button>

                                        <?php endif; ?>

                                        <br>

                                        <button
                                            type="submit"
                                            name="status"
                                            value="Direvisi"
                                            class="btn btn-warning btn-sm btn-block"
                                            onclick="return confirm('Minta mahasiswa untuk merevisi?')">

                                            <i class="fas fa-edit"></i>

                                            Minta Revisi

                                        </button>

                                        <br>

                                        <button
                                            type="submit"
                                            name="status"
                                            value="Ditolak"
                                            class="btn btn-danger btn-sm btn-block"
                                            onclick="return confirm('Yakin ingin menolak?')">

                                            <i class="fas fa-times"></i>

                                            Tolak

                                        </button>

                                    </td>

                                </form>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="alert alert-white mb-0">

                <i class="fas fa-info-circle"></i>

                Tidak ada pengajuan pending.

            </div>

        <?php endif; ?>

    </div>

</div>

<!-- =========================
    MENUNGGU REVISI MAHASISWA
========================== -->

<div class="card">

    <div class="card-header bg-secondary">

        <h3 class="card-title text-white">

            Menunggu Revisi Mahasiswa

        </h3>

    </div>

    <div class="card-body">

        <?php if (count($data_direvisi) > 0): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>Mahasiswa</th>
                            <th>Angkatan</th>
                            <th>Judul</th>
                            <th>Abstrak</th>
                            <th>Topik</th>
                            <th>Status</th>
                            <th>Catatan Revisi</th>
                            <th>Tanggal Review</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($data_direvisi as $row): ?>

                            <tr>

                                <td><?= $row['nama_mahasiswa'] ?></td>

                                <td><?= $row['angkatan'] ?></td>

                                <td><?= $row['judul'] ?></td>

                                <td><?= $row['abstrak'] ?></td>

                                <td><?= $row['nama_topik'] ?></td>

                                <td>

                                    <span class="badge badge-warning">

                                        Menunggu Revisi

                                    </span>

                                </td>

                                <td>

                                    <?php if (!empty($row['feedback'])): ?>

                                        <?= nl2br(esc($row['feedback'])) ?>

                                    <?php else: ?>

                                        <span class="text-muted">

                                            -

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= !empty($row['tgl_review'])
                                        ? date('d-m-Y', strtotime($row['tgl_review']))
                                        : '-' ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="alert alert-white mb-0">

                <i class="fas fa-info-circle"></i>

                Tidak ada pengajuan yang menunggu revisi.

            </div>

        <?php endif; ?>

    </div>

</div>

<!-- =========================
    SUDAH DIREVISI MAHASISWA
========================== -->

<div class="card">

    <div class="card-header bg-success">

        <h3 class="card-title text-white">

            Sudah Direvisi Mahasiswa

        </h3>

    </div>

    <div class="card-body">

        <?php if (count($data_sudah_direvisi) > 0): ?>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>

                        <tr>

                            <th>Mahasiswa</th>
                            <th>Angkatan</th>
                            <th>Judul Baru</th>
                            <th>Abstrak</th>
                            <th>Topik</th>
                            <th>Status</th>
                            <th>Feedback Lama</th>
                            <th width="190">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($data_sudah_direvisi as $row): ?>

                            <tr>

                                <td><?= $row['nama_mahasiswa'] ?></td>

                                <td><?= $row['angkatan'] ?></td>

                                <td><?= $row['judul'] ?></td>

                                <td><?= $row['abstrak'] ?></td>

                                <td><?= $row['nama_topik'] ?></td>

                                <td>

                                    <span class="badge badge-info">

                                        Sudah Direvisi

                                    </span>

                                </td>

                                <td>

                                    <?= $row['feedback_lama'] ?: '-' ?>

                                </td>

                                <form action="<?= base_url('dosen/proses_review') ?>" method="POST">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $row['id_pengajuan'] ?>">

                                    <td>

                                        <div class="form-group">

                                            <textarea
                                                name="feedback"
                                                rows="4"
                                                class="form-control"
                                                placeholder="Feedback review ulang"></textarea>

                                        </div>

                                        <?php if ($sisa_kuota > 0): ?>

                                            <button
                                                type="submit"
                                                name="status"
                                                value="ACC"
                                                class="btn btn-success btn-sm btn-block">

                                                <i class="fas fa-check"></i>

                                                ACC

                                            </button>

                                        <?php else: ?>

                                            <button
                                                class="btn btn-secondary btn-sm btn-block"
                                                disabled>

                                                <i class="fas fa-ban"></i>

                                                Kuota Penuh

                                            </button>

                                        <?php endif; ?>

                                        <br>

                                        <button
                                            type="submit"
                                            name="status"
                                            value="Direvisi"
                                            class="btn btn-warning btn-sm btn-block"
                                            onclick="return confirm('Minta revisi lagi?')">

                                            <i class="fas fa-edit"></i>

                                            Revisi Lagi

                                        </button>

                                        <br>

                                        <button
                                            type="submit"
                                            name="status"
                                            value="Ditolak"
                                            class="btn btn-danger btn-sm btn-block"
                                            onclick="return confirm('Yakin tolak?')">

                                            <i class="fas fa-times"></i>

                                            Tolak

                                        </button>

                                    </td>

                                </form>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="alert alert-white mb-0">

                <i class="fas fa-info-circle"></i>

                Tidak ada pengajuan yang sudah direvisi.

            </div>

        <?php endif; ?>

    </div>

</div>


<?= $this->endSection() ?>