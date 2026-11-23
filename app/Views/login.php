<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Pengajuan Skripsi</title>

    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">

    <style>
        body.login-page {
            /* Gradien biru kalem khas akademik/kampus */
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .login-logo {
            color: white !important;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
            font-weight: 300;
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
            <h2 class="mb-0 text-white"><b>SPS</b> Login</h2>
            <small class="text-white-50" style="font-size: 1rem;">Sistem Pengajuan Skripsi</small>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-body mt-2">
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

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" style="border-radius: 0.5rem;"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('proses_login') ?>" method="POST">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control form-control-lg" placeholder="NPM / NIDN" value="<?= old('username') ?>" style="border-radius: 0.5rem 0 0 0.5rem;">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white" style="border-radius: 0 0.5rem 0.5rem 0;"><span class="fas fa-user text-primary"></span></div>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" style="border-radius: 0.5rem 0 0 0.5rem;">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white" style="border-radius: 0 0.5rem 0.5rem 0;"><span class="fas fa-lock text-primary"></span></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="border-radius: 0.5rem;">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                    </button>
                </form>

                <hr class="my-4">

                <a href="<?= base_url('password') ?>" class="btn btn-outline-warning btn-block" style="border-radius: 0.5rem;">
                    <i class="fas fa-key mr-1"></i> Buat Password Baru
                </a>
                <a href="<?= base_url('publik/arsip') ?>" class="btn btn-outline-info btn-block mt-2" style="border-radius: 0.5rem;">
                    <i class="fas fa-book mr-1"></i> Arsip Skripsi Publik
                </a>

                <div class="text-center mt-3 text-muted" style="font-size: 0.85rem;">
                    <i class="fas fa-info-circle text-warning"></i> Lupa password? Hubungi Admin Prodi.
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>
</body>
</html>