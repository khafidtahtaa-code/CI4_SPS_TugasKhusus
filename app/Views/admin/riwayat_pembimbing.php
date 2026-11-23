<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card">

    <!-- <div class="card-header">
        <h3 class="card-title">
            Riwayat Penetapan Pembimbing
        </h3>
    </div> -->



    <div class="card-body">
        <!-- search -->
        <div class="mb-3">
            <input
                type="text"
                id="searchRiwayat"
                class="form-control"
                placeholder="Cari NPM, mahasiswa, judul, topik, pembimbing...">
        </div>
        <!-- end search -->


        <div id="tblRiwayat" class="table-responsive">
            <table class="table table-bordered table-hover">
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

                                <?php if ($row['status'] == 'Ditetapkan'): ?>

                                    <span class="badge badge-success">
                                        Ditetapkan
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-danger">
                                        Dibatalkan
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <button
                                    class="btn btn-info btn-sm"
                                    data-toggle="modal"
                                    data-target="#detail<?= $row['id_pengajuan'] ?>">

                                    Detail

                                </button>

                                <a href="<?= base_url('admin/edit_pembimbing?id=' . $row['id_pengajuan']) ?>"
                                    class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

                <?php if (count($data) == 0): ?>
                    <tr>
                        <td colspan="12" align="center" style="color:gray; font-style:italic;">
                            Tidak ada pengajuan
                        </td>
                    </tr>
                <?php endif; ?>

            </table>
        </div>

        <!-- modal -->
        <?php foreach ($data as $row): ?>

            <div
                class="modal fade"
                id="detail<?= $row['id_pengajuan'] ?>"
                tabindex="-1">

                <div class="modal-dialog modal-xl">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">
                                Detail Penetapan Pembimbing
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
                                    <th>Angkatan</th>
                                    <td><?= $row['angkatan'] ?></td>
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
                                    <th>Pembimbing 1</th>
                                    <td><?= $row['nama_pembimbing1'] ?></td>
                                </tr>

                                <tr>
                                    <th>Pembimbing 2</th>
                                    <td><?= $row['nama_pembimbing2'] ?></td>
                                </tr>

                                <tr>
                                    <th>Tanggal Penetapan</th>
                                    <td><?= $row['tgl_penetapan'] ?></td>
                                </tr>

                                <tr>
                                    <th>File PDF</th>

                                    <td>

                                        <?php if (!empty($row['file_pdf'])): ?>

                                            <a
                                                href="<?= base_url('uploads/' . $row['file_pdf']) ?>"
                                                target="_blank"
                                                class="btn btn-success btn-sm">

                                                Download PDF

                                            </a>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                Tidak ada file
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            </table>

                        </div>

                        <div class="modal-footer">

                            <a
                                href="<?= base_url('admin/edit_pembimbing?id=' . $row['id_pengajuan']) ?>"
                                class="btn btn-warning">

                                Edit Data

                            </a>

                            <a
                                href="<?= base_url('admin/hapus_pengajuan?id=' . $row['id_pengajuan']) ?>"
                                class="btn btn-danger"
                                onclick="return confirm('Yakin hapus permanen?')">

                                Hapus

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>
        <!-- end modal -->
        <?php if (session()->getFlashdata('error')): ?>
            <script>
                alert('<?= session()->getFlashdata('error') ?>');
            </script>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <script>
                alert('<?= session()->getFlashdata('success') ?>');
            </script>
        <?php endif; ?>

    </div>
</div>

<!-- search -->
<script>
    document.getElementById('searchRiwayat')
        .addEventListener('keyup', function() {

            let keyword = this.value.toLowerCase();

            let rows =
                document.querySelectorAll('#tblRiwayat tbody tr');

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