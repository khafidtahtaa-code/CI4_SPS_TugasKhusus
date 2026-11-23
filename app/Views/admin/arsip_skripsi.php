<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>


<?php if (session()->getFlashdata('pesan')): ?>

    <div class="alert alert-success">

        <?= session()->getFlashdata('pesan') ?>

    </div>

<?php endif; ?>

<div class="card">

    <div class="card-body">

        <div class="input-group">

            <div class="input-group-prepend">

                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>

            </div>

            <input
                type="text"
                id="searchArsip"
                class="form-control"
                placeholder="Cari NPM, nama mahasiswa, judul, topik, pembimbing, atau kata kunci...">

        </div>

        <small class="text-muted">
            Pencarian dilakukan secara langsung tanpa perlu menekan tombol.
        </small>

    </div>

</div>



<br>
<a href="<?= base_url('admin/tambah_arsip') ?>"
    class="btn btn-success">
    <i class="fas fa-plus"></i>
    Tambah Arsip Manual
</a>
<br><br>
<div class="small-box bg-info">

    <div class="inner">

        <h3>
            <?= count($alumni ?? []) + count($lulus_tahun_ini ?? []) ?>
        </h3>

        <p>Total Arsip Skripsi</p>

    </div>

    <div class="icon">
        <i class="fas fa-book-open"></i>
    </div>

</div>

<br>



<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Arsip Alumni (Import Excel)
        </h3>
    </div>

    <div class="card-body table-responsive">
        <table id="tableArsip" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Angkatan</th>
                    <th>Judul</th>
                    <th>Topik</th>
                    <th>Kata Kunci</th>
                    <th>Pembimbing 1</th>
                    <th>Pembimbing 2</th>
                    <th>Tahun Lulus</th>
                    <th style="width: 320px;">Abstrak</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_import = 1;
                if (!empty($alumni)): foreach ($alumni as $row):
                ?>

                        <tr>
                            <td><?= $no_import++ ?></td>
                            <td><?= esc($row['npm'] ?? '-') ?></td>
                            <td><?= esc($row['nama_mahasiswa'] ?? '-') ?></td>
                            <td><?= esc($row['angkatan'] ?? '-') ?></td>
                            <td><?= esc($row['judul'] ?? '-') ?></td>
                            <td><?= esc($row['nama_topik_arsip'] ?? '-') ?></td>
                            <td><?= esc($row['kata_kunci'] ?? '-') ?></td>
                            <td><?= esc($row['nama_pembimbing1_arsip'] ?? '-') ?></td>
                            <td><?= esc($row['nama_pembimbing2_arsip'] ?? '-') ?></td>
                            <td><?= esc($row['tahun_lulus'] ?? '-') ?></td>
                            <td style="text-align: justify; font-size: 13px;"><?= esc($row['abstrak'] ?? '-') ?></td>
                            <td>
                                <?php if (!empty($row['file_url'])): ?>
                                    <a href="<?= esc($row['file_url']) ?>"
                                        target="_blank"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-file-pdf"></i>
                                        Lihat PDF
                                    </a>
                                <?php else: ?> <i>File tidak tersedia</i> <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/delete_arsip_sistem?id=' . $row_lulus['id_arsip']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus permanen?')">

                                    <i class="fas fa-trash"></i>
                                    Hapus

                                </a>
                            </td>
                        </tr>

                    <?php endforeach;

                else: ?>

                    <tr>
                        <td colspan="13" style="text-align: center;">Belum ada data yang diimport</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
        <br>
        <a href="<?= base_url('admin/export_arsip_import') ?>"><button class="btn btn-info">Download Excel Data Import Only</button></a>
        <br><br>
    </div>
</div>



<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Mahasiswa Dalam Proses Bimbingan
        </h3>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Judul</th>
                    <th>Pembimbing 1</th>
                    <th>Pembimbing 2</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_proses = 1;
                if (!empty($dalam_proses)): foreach ($dalam_proses as $row_proses):
                ?>
                        <tr>
                            <td><?= $no_proses++ ?></td>
                            <td><?= esc($row_proses['npm'] ?? '-') ?></td>
                            <td><?= esc($row_proses['nama_mahasiswa'] ?? '-') ?></td>
                            <td><?= esc($row_proses['judul'] ?? '-') ?></td>
                            <td><?= esc($row_proses['nama_pembimbing1'] ?? '-') ?></td>
                            <td><?= esc($row_proses['nama_pembimbing2'] ?? '-') ?></td>
                            <td>

                                <?php if ($row_proses['status'] == 'Ditetapkan'): ?>

                                    <span class="badge badge-success">
                                        Ditetapkan
                                    </span>

                                <?php elseif ($row_proses['status'] == 'Menunggu Sidang'): ?>

                                    <span class="badge badge-warning">
                                        Menunggu Sidang
                                    </span>

                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data bimbingan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Arsip Kelulusan Sistem
        </h3>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Angkatan</th>
                    <th>Judul</th>
                    <th>Topik</th>
                    <th>Kata Kunci</th>
                    <th>Pembimbing 1</th>
                    <th>Pembimbing 2</th>
                    <th>Nilai Akhir</th>
                    <th>Tahun Lulus</th>
                    <th style="width: 320px;">Abstrak</th>
                    <th>Link File Skripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_sistem = 1;
                if (!empty($lulus_tahun_ini)): foreach ($lulus_tahun_ini as $row_lulus):
                ?>
                        <tr>
                            <td><?= $no_sistem++ ?></td>
                            <td><?= esc($row_lulus['npm'] ?? '-') ?></td>
                            <td><?= esc($row_lulus['nama_mahasiswa'] ?? '-') ?></td>
                            <td><?= esc($row_lulus['angkatan'] ?? '-') ?></td>
                            <td><?= esc($row_lulus['judul_skripsi'] ?? $row_lulus['judul'] ?? '-') ?></td>
                            <td><?= esc($row_lulus['nama_topik_arsip'] ?? '-') ?></td>
                            <td><?= esc(str_replace('[Sistem] ', '', $row_lulus['kata_kunci'] ?? '-')) ?></td>
                            <td><?= esc($row_lulus['pembimbing_1'] ?? $row_lulus['nama_pembimbing1_arsip'] ?? '-') ?></td>
                            <td><?= esc($row_lulus['pembimbing_2'] ?? $row_lulus['nama_pembimbing2_arsip'] ?? '-') ?></td>

                            <td><b><?= esc($row_lulus['nilai_skripsi'] ?? '-') ?></b></td>
                            <td><?= esc($row_lulus['tahun_lulus'] ?? '-') ?></td>
                            <td style="text-align: justify; font-size: 13px;"><?= esc($row_lulus['abstrak'] ?? '-') ?></td>

                            <td>
                                <?php if (!empty($row_lulus['file_skripsi'])): ?>
                                    <a
                                        href="<?= esc($row_lulus['file_skripsi']) ?>"
                                        target="_blank"
                                        class="btn btn-success btn-sm">

                                        <i class="fas fa-link"></i>
                                        Buka Drive

                                    </a>
                                <?php else: ?>
                                    <i>Belum ada link</i>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="<?= base_url('admin/batal_kelulusan?id_arsip=' . $row_lulus['id_arsip'] . '&id_pengajuan=' . $row_lulus['id_pengajuan']) ?>" onclick="return confirm('Yakin ingin membatalkan status lulus mahasiswa ini? Data mahasiswa akan dikembalikan ke halaman Kelola Sidang.')">
                                    <button type="button" class="btn btn-outline-primary btn-sm">Batal Lulus 🔄</button>
                                </a>

                                <div style=" margin-top: 5px;">
                                </div>

                                <a href="<?= base_url('admin/delete_arsip_sistem?id=' . $row_lulus['id_arsip']) ?>" onclick="return confirm('PERINGATAN: Yakin ingin menghapus data arsip ini secara PERMANEN dari sistem?')">
                                    <button type="button" style="background-color: #ffcccc; color: red; font-weight: bold; cursor: pointer; padding: 4px 8px; border: 1px solid red; border-radius: 4px;">Hapus ❌</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="14" style="text-align: center;">Belum ada mahasiswa yang diset lulus tahun ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="<?= base_url('admin/export_arsip_sistem') ?>"
            class="btn btn-info">

            <i class="fas fa-file-excel"></i>
            Export Excel

        </a>

    </div>
</div>

<script>
    document.getElementById('searchArsip').addEventListener('keyup', function() {

        let keyword = this.value.toLowerCase();

        document.querySelectorAll('table tbody tr').forEach(function(row) {

            let text = row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword) ?
                '' :
                'none';

        });

    });
</script>

<?= $this->endSection() ?>