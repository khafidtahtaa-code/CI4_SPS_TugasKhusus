<?= $this->extend('layout/template_mahasiswa') ?>
<?= $this->section('content') ?>


<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Filter Riwayat Pengajuan
        </h3>
    </div>

    <div class="card-body">

        <form method="GET" action="<?= base_url('mahasiswa/riwayat') ?>">

            <div class="form-group">

                <label>Pilih Riwayat</label>

                <select
                    name="filter_pengajuan"
                    class="form-control"
                    onchange="this.form.submit()">

                    <option value="semua">-- Tampilkan Semua --</option>

                    <?php if (!empty($semua_riwayat)): ?>
                        <?php foreach ($semua_riwayat as $index => $r): ?>

                            <option
                                value="<?= $r['id_pengajuan'] ?>"
                                <?= (isset($filter_terpilih) && $filter_terpilih == $r['id_pengajuan']) ? 'selected' : '' ?>>

                                Pengajuan ke-<?= $index + 1 ?>
                                (<?= !empty($r['tgl_pengajuan']) ? date('Y-m-d', strtotime($r['tgl_pengajuan'])) : '-' ?>)
                                - <?= $r['status'] ?>

                            </option>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </select>

            </div>

        </form>

    </div>
</div>

<div class="mb-3"></div>

<?php if (empty($riwayat)): ?>
    <div class="alert alert-info">

        Belum ada data pengajuan.

    </div>
<?php else: ?>
    <?php
    foreach ($riwayat as $row):
        $no_pengajuan = 1;
        foreach ($semua_riwayat as $index => $r) {
            if ($r['id_pengajuan'] == $row['id_pengajuan']) {
                $no_pengajuan = $index + 1;
                break;
            }
        }

        $is_acc = ($row['status_review'] == 'ACC');
        $is_ditolak = ($row['status_review'] == 'Ditolak');
        $is_dibatalkan = ($row['kondisi_data'] == 'Dibatalkan');
        $is_ditetapkan = (!empty($row['tgl_penetapan']));
    ?>

        <div class="mb-4"></div>
        <h3>Hasil Pengajuan <?= $no_pengajuan ?></h3>


        <div class="card">

            <div class="card-header bg-success text-white">

                <h3 class="card-title text-white">

                    1. Riwayat ACC Dosen

                </h3>

            </div>
            <div class="card-body">
                <?php if ($is_acc): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Abstrak</th>
                                    <th>Topik</th>
                                    <th>Calon Dosen</th>
                                    <th>Feedback</th>
                                    <th>Tgl Review</th>
                                    <th>Tgl Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $row['judul'] ?></td>
                                    <td><?= $row['abstrak'] ?></td>
                                    <td><?= $row['nama_topik'] ?></td>
                                    <td><?= $row['nama_dosen'] ?></td>
                                    <td><?= $row['feedback'] ?: '-' ?></td>
                                    <td><?= $row['tgl_review'] ?? '-' ?></td>
                                    <td><?= !empty($row['tgl_pengajuan']) ? date('Y-m-d', strtotime($row['tgl_pengajuan'])) : '-' ?></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                <?php else: ?>
                    <div class="alert alert-light">
                        Belum ada riwayat ACC dosen.
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="mb-4"></div>
        <div class="card">

            <div class="card-header bg-danger text-white">
                <h3 class="card-title text-white">

                    2. Riwayat Penolakan Dosen

                </h3>
            </div>

            <div class="card-body">
                <?php if ($is_ditolak): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Abstrak</th>
                                    <th>Topik</th>
                                    <th>Calon Dosen</th>
                                    <th>Feedback</th>
                                    <th>Tgl Review</th>
                                    <th>Tgl Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $row['judul'] ?></td>
                                    <td><?= $row['abstrak'] ?></td>
                                    <td><?= $row['nama_topik'] ?></td>
                                    <td><?= $row['nama_dosen'] ?></td>
                                    <td><?= $row['feedback'] ?: '-' ?></td>
                                    <td><?= $row['tgl_review'] ?? '-' ?></td>
                                    <td><?= !empty($row['tgl_pengajuan']) ? date('Y-m-d', strtotime($row['tgl_pengajuan'])) : '-' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="color:gray;">Tidak ada rekam jejak ditolak dosen pada pengajuan ini.</p>
                <?php endif; ?>

            </div>
        </div>

        <div class="mb-4"></div>
        <div class="card">

            <div class="card-header bg-primary text-white">
                <h3 class="card-title text-white">

                    3. Riwayat Penetapan

                </h3>

            </div>

            <div class="card-body">
                <?php if ($is_ditetapkan): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Abstrak</th>
                                    <th>Topik</th>
                                    <th>Pembimbing 1</th>
                                    <th>Pembimbing 2</th>
                                    <th>Tgl Penetapan</th>
                                    <th>Status</th>
                                    <th>File SK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $row['judul'] ?></td>
                                    <td><?= $row['abstrak'] ?></td>
                                    <td><?= $row['nama_topik'] ?></td>
                                    <td><?= $row['nama_pembimbing1'] ?? '-' ?></td>
                                    <td><?= $row['nama_pembimbing2'] ?? '-' ?></td>
                                    <td><?= !empty($row['tgl_penetapan']) ? date('Y-m-d', strtotime($row['tgl_penetapan'])) : '-' ?></td>

                                    <td>
                                        <?php if ($row['status'] == 'Lulus'): ?>
                                            <span style="color: green; font-weight: bold; background: #e6f9ed; padding: 4px 8px; border-radius: 4px;">LULUS 🎓</span>
                                        <?php else: ?>
                                            <span style="color: green; font-weight: bold;">Ditetapkan</span>
                                            <?php if ($is_dibatalkan): ?>
                                                <br><span style="color:red; font-size:11px; font-weight:bold;">(Dibatalkan Admin)</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($row['status'] == 'Lulus' && !empty($row['file_pdf'])): ?>
                                            <a
                                                href="<?= base_url('uploads/' . $row['file_pdf']) ?>"
                                                target="_blank"
                                                class="btn btn-success btn-sm">

                                                <i class="fas fa-download"></i>
                                                Download SK

                                            </a>
                                        <?php elseif ($is_dibatalkan): ?>
                                            <small class="text-danger fw-bold">❌ Berkas ditarik
                                            <?php else: ?>
                                                <?php
                                                // Kalkulasi sisa waktu secara Real-Time dengan PHP
                                                $sisa = 0;
                                                if (!empty($row['tgl_penetapan'])) {
                                                    $waktu_buka = strtotime($row['tgl_penetapan']) + (36 * 3600);
                                                    $sisa = max(0, $waktu_buka - time());
                                                }
                                                ?>

                                                <?php if ($sisa > 0): ?>
                                                    <?php
                                                    // Konversi detik ke format Hari/Jam/Menit
                                                    $hari  = floor($sisa / 86400);
                                                    $jam   = floor(($sisa % 86400) / 3600);
                                                    $menit = floor(($sisa % 3600) / 60);
                                                    ?>
                                                    <div style="color:orange; font-size:11px; font-weight:bold;">
                                                        ⏳ Mempersiapkan berkas...<br>
                                                        (Sisa: <?= $hari ?>h <?= $jam ?>j <?= $menit ?>m)
                                                    </div>
                                                <?php elseif (!empty($row['file_pdf'])): ?>
                                                    <a class="btn btn-success btn-sm"
                                                        href="<?= base_url('uploads/' . $row['file_pdf']) ?>"
                                                        target="_blank">
                                                        Download SK
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Belum ada file</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted"">Tidak ada rekam jejak penetapan oleh admin pada pengajuan ini.</p>
                <?php endif; ?>

            </div>
        </div>

        <div class=" mb-4">
            </div>
            <div class="card">

                <div class="card-header bg-warning">
                    <h3 class="card-title text-dark">

                        4. Riwayat Pembatalan / Ditolak Permanen

                    </h3>

                </div>

                <div class="card-body">
                    <?php if ($is_dibatalkan || $row['status'] == 'Ditolak' || $row['status'] == 'Ditolak Permanen'): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Abstrak</th>
                                        <th>Topik</th>
                                        <th>Calon Dosen</th>
                                        <th>Tgl Pengajuan</th>
                                        <th>Kondisi</th>
                                        <th>Feedback Admin</th>
                                        <th>Tgl Dibatalkan / Ditolak Permanen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $row['judul'] ?></td>
                                        <td><?= $row['abstrak'] ?></td>
                                        <td><?= $row['nama_topik'] ?></td>
                                        <td><?= $row['nama_dosen'] ?></td>
                                        <td><?= !empty($row['tgl_pengajuan']) ? date('Y-m-d', strtotime($row['tgl_pengajuan'])) : '-' ?></td>
                                        <td><span class="text-danger fw-bold">
                                                <?= (!empty($row['tgl_penetapan'])) ? 'Dibatalkan' : 'Ditolak Permanen' ?>
                                            </span></td>
                                        <td><?= esc($row['feedback_admin'] ?? '-') ?></td>
                                        <td><?= !empty($row['tgl_dibatalkan_ditolak']) ? date('Y-m-d H:i', strtotime($row['tgl_dibatalkan_ditolak'])) : '-' ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="color:gray;">Tidak ada rekam jejak pembatalan/penolakan permanen oleh admin pada pengajuan ini.</p>
                    <?php endif; ?>


                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>


    <?= $this->endSection() ?>