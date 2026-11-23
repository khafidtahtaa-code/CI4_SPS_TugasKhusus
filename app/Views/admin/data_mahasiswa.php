<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Tambah Mahasiswa
        </h3>
    </div>

    <div class="card-body">

        <form method="POST"
            action="<?= base_url('admin/tambah_mahasiswa') ?>">

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label>NPM Mahasiswa</label>
                        <input type="text"
                            name="npm"
                            class="form-control"
                            required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Mahasiswa</label>
                        <input type="text"
                            name="nama"
                            class="form-control"
                            required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text"
                            name="angkatan"
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

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Daftar Mahasiswa
        </h3>
    </div>

    <div class="card-body table-responsive p-0">

        <!-- SEARCH -->
        <input type="text"
            id="cariMahasiswa"
            class="form-control mb-3"
            placeholder="Cari NPM atau Nama Mahasiswa...">

        <!-- TABEL -->
        <table id="tblMahasiswa" class="table table-bordered table-hover text-nowrap">

            <thead>
                <tr>
                    <th>NPM</th>
                    <th>Nama</th>
                    <th>Angkatan</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($data as $row): ?>

                    <tr>
                        <td><?= $row['npm'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['angkatan'] ?></td>

                        <td>

                            <a href="<?= base_url('admin/edit_mahasiswa?npm=' . $row['npm']) ?>"
                                class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a href="<?= base_url('admin/hapus_mahasiswa?npm=' . $row['npm']) ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus data ini?')">

                                Hapus

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<!-- Script search -->
<script>
    document.getElementById('cariMahasiswa').addEventListener('keyup', function() {

        let keyword = this.value.toLowerCase();

        let rows = document.querySelectorAll('#tblMahasiswa tbody tr');

        rows.forEach(function(row) {

            let isiBaris = row.innerText.toLowerCase();

            if (isiBaris.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

        });

    });
</script>



<?= $this->endSection() ?>