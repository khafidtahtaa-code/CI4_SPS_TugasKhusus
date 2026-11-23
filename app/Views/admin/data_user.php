<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Tambah User
        </h3>
    </div>

    <div class="card-body">

        <form method="POST"
            action="<?= base_url('admin/tambah_user') ?>">

            <div class="row">

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text"
                            name="username"
                            class="form-control"
                            placeholder="NPM / NIDN / Username Admin"
                            required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Role</label>

                        <select name="role"
                            class="form-control"
                            required>

                            <option value="">Pilih Role</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dosen">Dosen</option>
                            <option value="admin">Admin</option>

                        </select>

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
            Filter User
        </h3>
    </div>

    <div class="card-body">

        <form method="GET">

            <div class="row">

                <div class="col-md-3">

                    <select name="filter_role"
                        class="form-control"
                        onchange="this.form.submit()">

                        <option value="">Semua Role</option>

                        <option value="mahasiswa"
                            <?= ($filter == 'mahasiswa') ? 'selected' : '' ?>>
                            Mahasiswa
                        </option>

                        <option value="dosen"
                            <?= ($filter == 'dosen') ? 'selected' : '' ?>>
                            Dosen
                        </option>

                        <option value="admin"
                            <?= ($filter == 'admin') ? 'selected' : '' ?>>
                            Admin
                        </option>

                    </select>

                </div>

            </div>

        </form>
    </div>
</div>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Daftar User
        </h3>
    </div>

    <div class="card-body table-responsive p-0">

        <input type="text"
            id="searchUser"
            class="form-control mb-3"
            placeholder="Cari Username...">

        <table class="table table-bordered table-hover text-nowrap">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Status Password</th>
                    <th>Role</th>
                    <th width="220">Aksi</th>
                </tr>
            </thead>

            <tbody id="tblUser">

                <?php foreach ($data_user as $row): ?>

                    <tr>

                        <td><?= $row['id'] ?></td>

                        <td><?= $row['username'] ?></td>

                        <td>

                            <?php if (empty($row['password'])): ?>

                                <span class="badge badge-danger">
                                    Belum Dibuat
                                </span>

                            <?php else: ?>

                                <span class="badge badge-success">
                                    Sudah Dibuat
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <span class="badge badge-info">
                                <?= ucfirst($row['role']) ?>
                            </span>

                        </td>

                        <td>

                            <a href="<?= base_url('admin/hapus_user?id=' . $row['id']) ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus user ini?')">

                                Hapus

                            </a>

                            <?php if (!empty($row['password'])): ?>

                                <a href="<?= base_url('admin/reset_password_admin/' . $row['username']) ?>"
                                    class="btn btn-warning btn-sm"
                                    onclick="return confirm('Reset password user ini?')">

                                    Reset PW

                                </a>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<!-- </div> -->
<!-- </div> -->

<script>
    document.getElementById('searchUser')
        .addEventListener('keyup', function() {

            let keyword = this.value.toLowerCase();

            let rows = document.querySelectorAll('#tblUser tr');

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