<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Daftar Mahasiswa Siap Sidang & Menunggu Penilaian</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>NPM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Angkatan</th>
                    <th>Judul Skripsi</th>
                    <th>Topik</th>
                    <th>Pembimbing 1</th>
                    <th>Pembimbing 2</th>
                    <th class="text-center">Berkas Draf (Google Drive)</th>
                    <th width="15%" class="text-center">Input Nilai & Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mahasiswa_sidang)): ?>
                    <?php $no = 1; foreach ($mahasiswa_sidang as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['npm'] ?></td>
                            <td><?= $row['nama_mahasiswa'] ?></td>
                            <td><?= $row['angkatan'] ?></td>
                            <td><?= $row['judul'] ?></td>
                            <td><?= $row['nama_topik'] ?></td>
                            <td><?= $row['nama_pembimbing1'] ?? '-' ?></td>
                            <td><?= $row['nama_pembimbing2'] ?? '-' ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['link_draf_skripsi'])): ?>
                                    <a href="<?= $row['link_draf_skripsi'] ?>" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-external-link-alt"></i> Buka Link
                                    </a>
                                <?php else: ?>
                                    <span class="badge badge-danger">Belum ada link</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form id="form-lulus-<?= $row['id_pengajuan'] ?>" action="<?= base_url('admin/proses_kelulusan') ?>" method="POST" onsubmit="return confirm('Yakin ingin meluluskan mahasiswa ini?')">
                                    <input type="hidden" name="id_pengajuan" value="<?= $row['id_pengajuan'] ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="nilai_skripsi" class="form-control" placeholder="Nilai (A/85)" required>
                                        <span class="input-group-append">
                                            <button type="submit" class="btn btn-success">Set Lulus 🎓</button>
                                        </span>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted font-italic py-4">
                            Saat ini belum ada mahasiswa yang mendaftar sidang.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>