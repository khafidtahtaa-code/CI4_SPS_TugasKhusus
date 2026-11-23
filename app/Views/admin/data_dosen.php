<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Form Tambah Dosen -->
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Tambah Dosen</h3>
    </div>

    <div class="card-body">

        <form method="POST" action="<?= base_url('admin/tambah_dosen') ?>">

            <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                        <label>NIDN</label>
                        <input type="text"
                            name="nidn"
                            class="form-control"
                            required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text"
                            name="nama"
                            class="form-control"
                            required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Bidang Keahlian</label>

                        <select name="bidang_keahlian"
                            class="form-control"
                            required>

                            <option value="">Pilih Topik</option>

                            <?php foreach ($topik as $t): ?>
                                <option value="<?= $t['nama_topik'] ?>">
                                    <?= $t['nama_topik'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>

                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Kuota</label>
                        <input type="number"
                            name="kuota_bimbingan"
                            class="form-control"
                            required>
                    </div>
                </div>

                <div class="col-md-2">
                    <label>&nbsp;</label>

                    <button type="submit"
                        class="btn btn-primary btn-block">
                        Tambah
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

<!-- Tabel Dosen -->
<div class="card">

    <div class="card-header">
        <h3 class="card-title">Daftar Dosen</h3>
    </div>

    <div class="card-body">

        <!-- Search -->
        <input type="text"
            id="searchDosen"
            class="form-control mb-3"
            placeholder="Cari NIDN, Nama, atau Bidang Keahlian...">

        <div class="table-responsive p-0 ">

            <table class="table table-bordered table-hover text-nowrap"
                id="tblDosen">

                <thead>
                    <tr>
                        <th>NIDN</th>
                        <th>Nama</th>
                        <th>Bidang Keahlian</th>
                        <th>Kuota</th>
                        <th>Total</th>
                        <th>Sisa</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($data as $row):

                        $total = max(0, $row['total_bimbingan']);
                        $sisa  = $row['kuota_bimbingan'] - $total;
                    ?>

                        <tr>

                            <td><?= $row['nidn'] ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['bidang_keahlian'] ?? '-' ?></td>
                            <td><?= $row['kuota_bimbingan'] ?></td>
                            <td><?= $total ?></td>
                            <td><?= $sisa ?></td>

                            <td>

                                <?php if ($total >= $row['kuota_bimbingan']) : ?>

                                    <span class="badge badge-danger">
                                        Penuh
                                    </span>

                                <?php else : ?>

                                    <span class="badge badge-success">
                                        Tersedia
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <a href="<?= base_url('admin/edit_dosen?nidn=' . $row['nidn']) ?>"
                                    class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <a href="<?= base_url('admin/hapus_dosen?nidn=' . $row['nidn']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus dosen ini?')">

                                    Hapus

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
    document.getElementById('searchDosen').addEventListener('keyup', function() {

        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tblDosen tbody tr');

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