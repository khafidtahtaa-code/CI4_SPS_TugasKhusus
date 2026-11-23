<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Password - SPS</title>

    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">

    <style>
        body.login-page {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .login-logo {
            color: white !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }
        .login-box .card {
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: none;
        }
        .card-header {
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }
    </style>
</head>

<body class="hold-transition login-page">

    <div class="login-box">
        <div class="login-logo mb-4">
            <h2 class="mb-0 text-white"><b>SPS</b> Aktivasi</h2>
            <small class="text-white-50" style="font-size: 1rem;">Sistem Pengajuan Skripsi</small>
        </div>

        <div class="card card-outline card-warning">
            <div class="card-body mt-2">
                <p class="login-box-msg text-muted">Silakan buat password untuk akun Anda.</p>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger" style="border-radius: 0.5rem;">
                        <ul class="mb-0 pl-3">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" style="border-radius: 0.5rem;"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('proses_password') ?>">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control form-control-lg" placeholder="NPM / NIDN" value="<?= old('username') ?>" required style="border-radius: 0.5rem 0 0 0.5rem;">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white" style="border-radius: 0 0.5rem 0.5rem 0;"><span class="fas fa-id-card text-warning"></span></div>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Buat Password Baru" required style="border-radius: 0.5rem 0 0 0.5rem;">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white" style="border-radius: 0 0.5rem 0.5rem 0;"><span class="fas fa-lock text-warning"></span></div>
                        </div>
                    </div>
<br>
                    <small class="text-muted d-block mb-4 mt-2" style="line-height: 1.2;">
                        <i class="fas fa-info-circle text-info"></i> Password wajib terdiri dari kombinasi huruf dan angka (simbol opsional).
                    </small>

                    <button type="submit" class="btn btn-warning btn-block btn-lg font-weight-bold" style="border-radius: 0.5rem;">
                        <i class="fas fa-save mr-1"></i> Simpan Password
                    </button>
                </form>

                <hr class="my-4">

                <a href="<?= base_url('/') ?>" class="btn btn-light btn-block text-muted" style="border-radius: 0.5rem;">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
</body>
</html>