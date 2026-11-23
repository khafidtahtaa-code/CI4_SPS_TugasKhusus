<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/',                 'Auth::index');
$routes->post('/proses_login',    'Auth::proses_login');
$routes->get('/password',         'Auth::password');
$routes->post('/proses_password', 'Auth::proses_password');
$routes->get('/logout',           'Auth::logout');

// Mahasiswa
$routes->get('mahasiswa/dashboard',         'Mahasiswa::dashboard');
$routes->get('mahasiswa/pengajuan',         'Mahasiswa::pengajuan');
$routes->post('mahasiswa/proses_pengajuan', 'Mahasiswa::proses_pengajuan');
$routes->get('mahasiswa/status',            'Mahasiswa::status');
$routes->get('mahasiswa/riwayat',           'Mahasiswa::riwayat');
$routes->get('mahasiswa/edit',              'Mahasiswa::edit');
$routes->post('mahasiswa/proses_edit',      'Mahasiswa::proses_edit');
$routes->get('mahasiswa/hapus',             'Mahasiswa::hapus');
$routes->post('mahasiswa/cek_duplikasi',    'Mahasiswa::cek_duplikasi');
$routes->get('mahasiswa/logout',                'Mahasiswa::logout');
$routes->post('mahasiswa/proses_daftar_sidang', 'Mahasiswa::proses_daftar_sidang');


// Logbook Mahasiswa
$routes->get('mahasiswa/logbook',           'Mahasiswa::logbook');
$routes->post('mahasiswa/simpan_logbook',   'Mahasiswa::simpan_logbook');
$routes->get('mahasiswa/hapus_logbook',     'Mahasiswa::hapus_logbook');


// Dosen
$routes->get('dosen/dashboard',             'Dosen::dashboard');
$routes->get('dosen/review',                'Dosen::review');
$routes->post('dosen/proses_review',        'Dosen::proses_review');
$routes->get('dosen/riwayat_review',        'Dosen::riwayat_review');
$routes->get('dosen/mahasiswa_bimbingan',   'Dosen::mahasiswa_bimbingan');
$routes->get('dosen/logout',                'Dosen::logout');


// Logbook Dosen
$routes->get('dosen/logbook',               'Dosen::logbook_bimbingan');
$routes->get('dosen/detail_logbook/(:num)', 'Dosen::detail_logbook/$1');
$routes->post('dosen/validasi_logbook',     'Dosen::validasi_logbook');


// Admin
$routes->get('admin/dashboard',                     'Admin::dashboard');

// User
$routes->get('admin/data_user',                     'Admin::data_user');
$routes->post('admin/tambah_user',                  'Admin::tambah_user');
$routes->get('admin/hapus_user',                    'Admin::hapus_user');
$routes->get('admin/reset_password_admin/(:any)',   'Admin::reset_password_admin/$1');

// Mahasiswa
$routes->get('admin/data_mahasiswa',                'Admin::data_mahasiswa');
$routes->post('admin/tambah_mahasiswa',             'Admin::tambah_mahasiswa');
$routes->get('admin/edit_mahasiswa',                'Admin::edit_mahasiswa');
$routes->post('admin/update_mahasiswa',             'Admin::update_mahasiswa');
$routes->get('admin/hapus_mahasiswa',               'Admin::hapus_mahasiswa');

// Dosen
$routes->get('admin/data_dosen',                    'Admin::data_dosen');
$routes->post('admin/tambah_dosen',                 'Admin::tambah_dosen');
$routes->get('admin/edit_dosen',                    'Admin::edit_dosen');
$routes->post('admin/update_dosen',                 'Admin::update_dosen');
$routes->get('admin/hapus_dosen',                   'Admin::hapus_dosen');

// Topik
$routes->get('admin/data_topik',                    'Admin::data_topik');
$routes->post('admin/tambah_topik',                 'Admin::tambah_topik');
$routes->get('admin/edit_topik',                    'Admin::edit_topik');
$routes->post('admin/update_topik',                 'Admin::update_topik');
$routes->get('admin/hapus_topik',                   'Admin::hapus_topik');

// Pengajuan
$routes->get('admin/data_pengajuan',                'Admin::data_pengajuan');
$routes->post('admin/tolak_pengajuan',              'Admin::tolak_pengajuan');

// Penetapan
$routes->get('admin/penetapan_pembimbing',          'Admin::penetapan_pembimbing');
$routes->post('admin/proses_penetapan',             'Admin::proses_penetapan');

// Riwayat Pembimbing Utama
$routes->get('admin/riwayat_pembimbing',            'Admin::riwayat_pembimbing');
$routes->get('admin/edit_pembimbing',               'Admin::edit_pembimbing');
$routes->post('admin/update_pembimbing',            'Admin::update_pembimbing');
$routes->post('admin/batalkan',                    'Admin::batalkan');
$routes->get('admin/hapus_pengajuan',              'Admin::hapus_pengajuan');

// Halaman Baru: Daftar Pembatalan Penetapan Admin
$routes->get('admin/batal_penetapan',               'Admin::batal_penetapan');
$routes->get('admin/hapus_permanen',                'Admin::hapus_permanen');

// Arsip Skripsi Kelulusan
$routes->get('admin/arsip_skripsi',                 'Admin::arsip_skripsi');
$routes->get('admin/tambah_arsip',                  'Admin::tambah_arsip');
$routes->post('admin/simpan_arsip',                 'Admin::simpan_arsip');
$routes->get('admin/delete_arsip',                  'Admin::delete_arsip');
$routes->get('admin/auto_arsip',                    'Admin::auto_arsip');

// Statistik
$routes->get('admin/statistik',                     'Admin::statistik');

// Publik (tanpa login)
$routes->get('publik/arsip',                        'Publik::arsip');
$routes->get('publik/detail/(:num)',                'Publik::detail/$1');

// Import Excel
$routes->get('admin/import_excel',               'Admin::import_excel');
$routes->post('admin/proses_import_mahasiswa',   'Admin::proses_import_mahasiswa');
$routes->post('admin/proses_import_dosen',       'Admin::proses_import_dosen');
$routes->post('admin/proses_import_arsip',       'Admin::proses_import_arsip');
$routes->get('admin/download_template_mahasiswa','Admin::download_template_mahasiswa');
$routes->get('admin/download_template_dosen',    'Admin::download_template_dosen');
$routes->get('admin/download_template_arsip',    'Admin::download_template_arsip');

// Eksport arsip
$routes->get('admin/export_arsip_import',        'Admin::export_arsip_import');
$routes->get('admin/export_arsip_sistem',        'Admin::export_arsip_sistem');

// Kelola Link Sidang
$routes->get('admin/kelola_sidang', 'Admin::kelola_sidang');
$routes->post('admin/proses_kelulusan', 'Admin::proses_kelulusan');

// Logout
$routes->get('admin/logout',                 'Admin::logout');
