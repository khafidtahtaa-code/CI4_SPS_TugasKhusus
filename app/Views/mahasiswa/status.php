<?= $this->extend('layout/template_mahasiswa') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('pesan') ?>
        </div>
    <?php endif; ?>


    <?php if (!empty($data['feedback_admin'])): ?>

        <div class="alert alert-warning">

            <h5>
                <i class="fas fa-exclamation-circle"></i>
                Pesan Admin
            </h5>

            <?= esc($data['feedback_admin']) ?>

        </div>

    <?php endif; ?>


    <?php if ($data): ?>



        <!-- ================= DATA PENGAJUAN ================= -->

        <div class="card card-primary">

            <div class="card-header">

                <h3 class="card-title">
                    Data Pengajuan Skripsi
                </h3>

            </div>

            <div class="card-body">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>
                            <th>Judul</th>
                            <th>Abstrak</th>
                            <th>Topik</th>
                            <th>Calon Dosen</th>
                            <th>Tanggal</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td><?= $data['judul'] ?></td>

                            <td><?= $data['abstrak'] ?></td>

                            <td><?= $data['nama_topik'] ?></td>

                            <td><?= $data['nama_dosen'] ?></td>

                            <td>
                                <?= !empty($data['tgl_pengajuan'])
                                    ? date('d-m-Y', strtotime($data['tgl_pengajuan']))
                                    : '-' ?>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>



        <!-- ================= REVIEW DOSEN ================= -->

        <div class="card card-info">

            <div class="card-header">

                <h3 class="card-title">
                    Hasil Review Dosen
                </h3>

            </div>

            <div class="card-body">

                <?php if (empty($data['status_review']) || $data['status_review'] == 'Pending'): ?>

                    <div class="alert alert-primary">

                        <i class="fas fa-clock"></i>

                        Menunggu keputusan dari dosen.

                    </div>

                <?php elseif ($data['status_review'] == 'Direvisi'): ?>

                    <table class="table table-bordered">

                        <thead>

                            <tr>

                                <th>Status</th>

                                <th>Catatan Revisi</th>

                                <th>Tanggal Review</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>

                                    <span class="badge badge-warning">

                                        Direvisi

                                    </span>

                                </td>

                                <td>

                                    <?= $data['feedback'] ?? '-' ?>

                                </td>

                                <td>

                                    <?= !empty($data['tgl_review'])
                                        ? date('d-m-Y', strtotime($data['tgl_review']))
                                        : '-' ?>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                    <div class="alert alert-warning mt-3">

                        <b>Perlu Revisi</b>

                        <br>

                        Silakan edit pengajuan sesuai catatan dosen.

                    </div>

                <?php else: ?>

                    <table class="table table-bordered">

                        <thead>

                            <tr>

                                <th>Status</th>

                                <th>Komentar Dosen</th>

                                <th>Tanggal Review</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>

                                    <?php if ($data['status_review'] == 'ACC'): ?>

                                        <span class="badge badge-success">

                                            ACC

                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-danger">

                                            Ditolak

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= $data['feedback'] ?? '-' ?>

                                </td>

                                <td>

                                    <?= !empty($data['tgl_review'])
                                        ? date('d-m-Y', strtotime($data['tgl_review']))
                                        : '-' ?>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

        </div>



        <!-- ================= PENETAPAN ADMIN ================= -->

        <div class="card card-success">

            <div class="card-header">

                <h3 class="card-title">
                    Hasil Akhir Admin
                </h3>

            </div>

            <div class="card-body">

                <?php if ($data['status'] == 'Ditetapkan' && $data['kondisi_data'] == 'Aktif'): ?>

                    <table class="table table-bordered">

                        <thead>

                            <tr>

                                <th>Pembimbing 1</th>

                                <th>Pembimbing 2</th>

                                <th>Status</th>

                                <th>Tanggal Penetapan</th>

                                <th>File SK</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td><?= $data['nama_pembimbing1'] ?? '-' ?></td>

                                <td><?= $data['nama_pembimbing2'] ?? '-' ?></td>

                                <td>
                                    <span class="badge badge-success">
                                        Ditetapkan
                                    </span>
                                </td>

                                <td>
                                    <?= !empty($data['tgl_penetapan'])
                                        ? date('d-m-Y', strtotime($data['tgl_penetapan']))
                                        : '-' ?>
                                </td>

                                <td id="pdf-cell">

                                    <?php if ($sisa_detik > 0): ?>

                                        <div id="countdown-box" class="text-warning font-weight-bold">

                                            Mempersiapkan berkas...

                                        </div>

                                    <?php else: ?>

                                        <a href="<?= base_url('uploads/' . $data['file_pdf']) ?>"
                                            target="_blank"
                                            class="btn btn-success btn-sm">

                                            <i class="fas fa-download"></i>

                                            Download SK

                                        </a>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                    <div class="alert alert-success mt-3">

                        Selamat, dosen pembimbing Anda telah ditetapkan.

                    </div>



                <?php elseif ($data['status'] == 'Menunggu Sidang'): ?>

                    <div class="alert alert-info">

                        <h5>

                            <i class="fas fa-hourglass-half"></i>

                            Menunggu Sidang

                        </h5>

                        Pendaftaran sidang berhasil.

                        <br><br>

                        <strong>Link Draf :</strong>

                        <br>

                        <a href="<?= $data['link_draf_skripsi'] ?>" target="_blank">

                            <?= $data['link_draf_skripsi'] ?>

                        </a>

                    </div>



                <?php elseif ($data['status'] == 'Lulus'): ?>

                    <div class="alert alert-success">

                        <h4>

                            🎓 Selamat!

                        </h4>

                        Anda telah dinyatakan

                        <b>LULUS</b>

                        dan data skripsi telah diarsipkan.

                        <br><br>

                        <?php if (!empty($data['file_pdf'])): ?>

                            <a href="<?= base_url('uploads/' . $data['file_pdf']) ?>"
                                target="_blank"
                                class="btn btn-success">

                                <i class="fas fa-file-pdf"></i>

                                Lihat SK

                            </a>

                        <?php endif; ?>

                    </div>



                <?php elseif ($data['status'] == 'Ditolak Permanen'): ?>

                    <div class="alert alert-warning">

                        <h5>

                            Pengajuan Ditolak Permanen

                        </h5>

                        Silakan melakukan pengajuan baru.

                        <br><br>

                        <a href="<?= base_url('mahasiswa/pengajuan') ?>"
                            class="btn btn-primary">

                            Ajukan Judul Baru

                        </a>

                    </div>



                <?php elseif (
                    $data['status_review'] == 'Ditolak'
                    && $data['kondisi_data'] == 'Dibatalkan'
                ): ?>

                    <div class="alert alert-warning">

                        Pengajuan ditolak secara permanen oleh Admin.

                    </div>



                <?php elseif ($data['kondisi_data'] == 'Dibatalkan'): ?>

                    <div class="alert alert-warning">

                        Penetapan dibatalkan oleh Admin.

                        <br>

                        Anda dapat melakukan pengajuan kembali.

                    </div>



                <?php elseif (
                    $data['status_review'] == 'Ditolak'
                    && $data['kondisi_data'] == 'Aktif'
                ): ?>

                    <div class="alert alert-warning">

                        Pengajuan ditolak dosen.

                        Menunggu keputusan Admin.

                    </div>



                <?php else: ?>

                    <div class="alert alert-primary">

                        Menunggu SK atau Penetapan Pembimbing dari Admin.

                    </div>

                <?php endif; ?>

            </div>

        </div>

        <hr>

        <?php if (
            empty($data['status_review']) ||
            $data['status_review'] == 'Pending' ||
            $data['status_review'] == 'Direvisi'
        ): ?>

            <a href="<?= base_url('mahasiswa/edit?id=' . $data['id_pengajuan']) ?>"
                class="btn btn-warning">

                <i class="fas fa-edit"></i>

                <?= $data['status_review'] == 'Direvisi'
                    ? 'Edit & Resubmit'
                    : 'Edit Pengajuan' ?>

            </a>

            <?php if (
                empty($data['status_review']) ||
                $data['status_review'] == 'Pending'
            ): ?>

                <a href="<?= base_url('mahasiswa/hapus?id=' . $data['id_pengajuan']) ?>"
                    onclick="return confirm('Yakin ingin menghapus pengajuan ini?')"
                    class="btn btn-warning">

                    <i class="fas fa-trash"></i>

                    Hapus

                </a>

            <?php endif; ?>

        <?php endif; ?>

    <?php else: ?>

        <div class="alert alert-info">

            Anda belum mengirimkan pengajuan skripsi.

        </div>

    <?php endif; ?>


</div>

<script>
    let sisaDetik = <?= $sisa_detik ?? 0 ?>;

    function jalankanTimer() {

        let targetBox = document.getElementById('countdown-box');

        if (!targetBox) return;

        if (sisaDetik <= 0) {

            let cell = document.getElementById('pdf-cell');

            if (cell) {

                cell.innerHTML = `
                <a href="<?= base_url('uploads/' . ($data['file_pdf'] ?? '')) ?>"
                   target="_blank"
                   class="btn btn-success btn-sm">

                    <i class="fas fa-download"></i>

                    Download SK

                </a>
            `;

            }

            return;

        }

        let hari = Math.floor(sisaDetik / 86400);

        let jam = Math.floor((sisaDetik % 86400) / 3600);

        let menit = Math.floor((sisaDetik % 3600) / 60);

        let detik = sisaDetik % 60;

        targetBox.innerHTML =
            "PDF tersedia dalam " +
            hari + " hari " +
            jam + " jam " +
            menit + " menit " +
            detik + " detik";

        sisaDetik--;

    }

    if (sisaDetik > 0) {

        jalankanTimer();

        let interval = setInterval(function() {

            jalankanTimer();

            if (sisaDetik < 0) {

                clearInterval(interval);

            }

        }, 1000);

    }
</script>

<?= $this->endSection() ?>