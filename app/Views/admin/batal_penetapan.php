<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Data Pembatalan Penetapan</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>NPM</th>
                    <th>Mahasiswa</th>
                    <th>Judul</th>
                    <th>Topik</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($batal) && count($batal) > 0): ?>
                    <?php foreach ($batal as $row): ?>
                    <tr>
                        <td><?= esc($row['npm']) ?></td>
                        <td><?= esc($row['nama_mahasiswa'] ?? 'Tanpa Nama') ?></td> 
                        <td><?= esc($row['judul']) ?></td>
                        <td><?= esc($row['id_topik'] ?? '-') ?></td>
                        <td><span class="badge badge-warning"><?= esc($row['status']) ?></span></td>
                        <td><?= esc($row['feedback_admin'] ?? '-') ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/hapus_permanen?id=' . $row['id_pengajuan']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus permanen data ini?')" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted font-italic py-3">Tidak ada data yang dibatalkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card card-danger">
    <div class="card-header">
        <h3 class="card-title">Data Ditolak Permanen</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>NPM</th>
                    <th>Mahasiswa</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Feedback</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($ditolak) && count($ditolak) > 0): ?>
                    <?php foreach ($ditolak as $row): ?>
                    <tr>
                        <td><?= esc($row['npm']) ?></td>
                        <td><?= esc($row['nama_mahasiswa'] ?? 'Tanpa Nama') ?></td> 
                        <td><?= esc($row['judul']) ?></td>
                        <td><span class="badge badge-danger"><?= esc($row['status']) ?></span></td>
                        <td><?= esc($row['feedback_admin'] ?? '-') ?></td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/hapus_permanen?id=' . $row['id_pengajuan']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus permanen data ini?')" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted font-italic py-3">Tidak ada data yang ditolak permanen.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>