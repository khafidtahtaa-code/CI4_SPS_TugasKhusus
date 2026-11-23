<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card">

    <!-- <div class="card-header">

        <h3 class="card-title">
            Data Pengajuan Skripsi
        </h3>

    </div> -->

    <div class="card-body">

        <input
            type="text"
            id="searchPengajuan"
            class="form-control mb-3"
            placeholder="Cari mahasiswa atau judul skripsi...">

        <div class="table-responsive p-0">

            <table class="table table-bordered table-hover text-nowrap" id="tblPengajuan">

                <thead>

                    <tr>
                        <th>Mahasiswa</th>
                        <th>Judul</th>
                        <th>Topik</th>
                        <th>Dosen</th>
                        <th>Status Review</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="150">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($semua_pengajuan as $row): ?>

                        <tr>

                            <td>
                                <?= $row['nama_mahasiswa'] ?>
                                <br>
                                <small><?= $row['npm'] ?></small>
                            </td>

                            <td><?= $row['judul'] ?></td>

                            <td><?= $row['nama_topik'] ?></td>

                            <td><?= $row['nama_dosen'] ?></td>

                            <td>

                                <?php if ($row['status_review'] == 'ACC'): ?>

                                    <span class="badge badge-success">
                                        ACC
                                    </span>

                                <?php elseif ($row['status_review'] == 'Ditolak'): ?>

                                    <span class="badge badge-danger">
                                        Ditolak
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-warning">
                                        Pending
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php if ($row['status'] == 'Ditetapkan'): ?>

                                    <span class="badge badge-primary">
                                        Ditetapkan
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-secondary">
                                        <?= $row['status'] ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>
                                <?= date('d-m-Y', strtotime($row['tgl_pengajuan'])) ?>
                            </td>

                            <td>

                                <button
                                    class="btn btn-info btn-sm"
                                    data-toggle="modal"
                                    data-target="#detail<?= $row['id_pengajuan'] ?>">
                                    Detail
                                </button>

                                <?php if ($row['status'] != 'Ditolak Permanen'): ?>

                                    <button
                                        class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#tolak<?= $row['id_pengajuan'] ?>">
                                        Tolak
                                    </button>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>


        <!-- modal detail -->
        <?php foreach ($semua_pengajuan as $row): ?>

            <div class="modal fade"
                id="detail<?= $row['id_pengajuan'] ?>">

                <div class="modal-dialog modal-lg">

                    <div class="modal-content">

                        <div class="modal-header bg-info">

                            <h4 class="modal-title">
                                Detail Pengajuan
                            </h4>

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
                                    <th width="200">Mahasiswa</th>
                                    <td><?= $row['nama_mahasiswa'] ?></td>
                                </tr>

                                <tr>
                                    <th>NPM</th>
                                    <td><?= $row['npm'] ?></td>
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
                                    <th>Calon Dosen</th>
                                    <td><?= $row['nama_dosen'] ?></td>
                                </tr>

                                <tr>
                                    <th>Abstrak</th>
                                    <td><?= nl2br($row['abstrak']) ?></td>
                                </tr>

                                <tr>
                                    <th>Feedback Dosen</th>
                                    <td><?= $row['feedback'] ?? '-' ?></td>
                                </tr>

                                <tr>
                                    <th>Feedback Admin</th>
                                    <td><?= $row['feedback_admin'] ?? '-' ?></td>
                                </tr>

                                <tr>
                                    <th>Status Review</th>
                                    <td><?= $row['status_review'] ?? 'Pending' ?></td>
                                </tr>

                                <tr>
                                    <th>Status Pengajuan</th>
                                    <td><?= $row['status'] ?></td>
                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>
        <!-- end modal detail -->

        <!--modal tolak permanen -->
        <?php foreach ($semua_pengajuan as $row): ?>

            <div class="modal fade"
                id="tolak<?= $row['id_pengajuan'] ?>">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <form
                            method="post"
                            action="<?= base_url('admin/tolak_pengajuan') ?>">

                            <div class="modal-header bg-danger">

                                <h4 class="modal-title">
                                    Tolak Permanen
                                </h4>

                            </div>

                            <div class="modal-body">

                                <input
                                    type="hidden"
                                    name="id_pengajuan"
                                    value="<?= $row['id_pengajuan'] ?>">

                                <div class="form-group">

                                    <label>Alasan Penolakan</label>

                                    <textarea
                                        name="feedback_admin"
                                        class="form-control"
                                        rows="4"
                                        required></textarea>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal">

                                    Batal

                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-danger">

                                    Tolak Permanen

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>
        <!-- end tolak permanen -->

    </div>

</div>

<script>
    document.getElementById('searchPengajuan')
        .addEventListener('keyup', function() {

            let keyword = this.value.toLowerCase();

            let rows = document.querySelectorAll('#tblPengajuan tbody tr');

            rows.forEach(function(row) {

                let text = row.innerText.toLowerCase();

                row.style.display =
                    text.includes(keyword) ?
                    '' :
                    'none';

            });

        });
</script>


<?= $this->endSection() ?>