<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Tambah Topik Skripsi
        </h3>
    </div>

    <div class="card-body">

        <form method="POST"
            action="<?= base_url('admin/tambah_topik') ?>">

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label>ID Topik</label>

                        <input type="text"
                            name="id_topik"
                            class="form-control"
                            required>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="form-group">
                        <label>Nama Topik</label>

                        <input type="text"
                            name="nama_topik"
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
            Daftar Topik Skripsi
        </h3>
    </div>

    <div class="card-body">

        <input type="text"
            id="searchTopik"
            class="form-control mb-3"
            placeholder="Cari Topik...">

        <div class="table-responsive p-0">

            <table class="table table-bordered table-hover text-nowrap"
                id="tblTopik">

                <thead>
                    <tr>
                        <th width="120">ID Topik</th>
                        <th>Nama Topik</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($data as $row): ?>

                        <tr>

                            <td><?= $row['id_topik'] ?></td>

                            <td><?= $row['nama_topik'] ?></td>

                            <td>

                                <a href="<?= base_url('admin/edit_topik?id_topik=' . $row['id_topik']) ?>"
                                    class="btn btn-warning btn-sm">

                                    Edit

                                </a>

                                <a href="<?= base_url('admin/hapus_topik?id_topik=' . $row['id_topik']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus topik ini?')">

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
    document.getElementById('searchTopik').addEventListener('keyup', function() {

        let keyword = this.value.toLowerCase();

        let rows = document.querySelectorAll('#tblTopik tbody tr');

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