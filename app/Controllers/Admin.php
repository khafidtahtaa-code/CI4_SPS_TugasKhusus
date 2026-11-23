<?php

namespace App\Controllers;

use App\Models\ArsipModel;
use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\TopikModel;
use App\Models\PengajuanModel;
use App\Models\ReviewModel;
use App\Models\PembimbingModel;

class Admin extends BaseController
{
    // 1. TAMBAHKAN BARIS INI UNTUK MENGHILANGKAN ERROR/WARNING
    protected $db;
    protected $userModel;
    protected $mahasiswaModel;
    protected $dosenModel;
    protected $topikModel;
    protected $pengajuanModel;
    protected $reviewModel;
    protected $pembimbingModel;
    protected $arsipModel;

    public function __construct()
    {
        $this->db              = \Config\Database::connect();
        $this->userModel       = new UserModel();
        $this->mahasiswaModel  = new MahasiswaModel();
        $this->dosenModel      = new DosenModel();
        $this->topikModel      = new TopikModel();
        $this->pengajuanModel  = new PengajuanModel();
        $this->reviewModel     = new ReviewModel();
        $this->pembimbingModel = new PembimbingModel();
        $this->arsipModel      = new ArsipModel(); // ← Pastikan baris ini sudah aktif
    }

    private function cekRole()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/');
        }
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $u_mhs = $this->userModel->where('role', 'mahasiswa')->countAllResults();
        $u_dsn = $this->userModel->where('role', 'dosen')->countAllResults();
        $u_adm = $this->userModel->where('role', 'admin')->countAllResults();

        $total_mahasiswa = $this->mahasiswaModel->countAllResults();
        $total_dosen     = $this->dosenModel->countAllResults();
        $total_topik     = $this->topikModel->countAllResults();
        $total_pengajuan = $this->pengajuanModel->countAllResults();

        $belum_tetap = $this->db->query("
            SELECT COUNT(*) as total FROM pengajuan
            JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
            WHERE review_pengajuan.status = 'ACC'
            AND pengajuan.status != 'Ditetapkan'
            AND pengajuan.kondisi_data = 'Aktif'
        ")->getRowArray();

        $sudah_tetap = $this->pengajuanModel
            ->where('status', 'Ditetapkan')
            ->where('kondisi_data', 'Aktif')
            ->countAllResults();

        $total_arsip = $this->pengajuanModel
            ->where('kondisi_data', 'Diarsipkan')
            ->countAllResults();

        $batal_ditetapkan = $this->pengajuanModel
            ->where('kondisi_data', 'Dibatalkan')
            ->countAllResults();

        return view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'u_mhs'          => $u_mhs,
            'u_dsn'          => $u_dsn,
            'u_adm'          => $u_adm,
            'total_mahasiswa' => $total_mahasiswa,
            'total_dosen'    => $total_dosen,
            'total_topik'    => $total_topik,
            'total_pengajuan' => $total_pengajuan,
            'belum_tetap'    => $belum_tetap['total'],
            'sudah_tetap'    => $sudah_tetap,
            'total_arsip'    => $total_arsip,
            'batal_ditetapkan' => $batal_ditetapkan,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DATA USER
    |--------------------------------------------------------------------------
    */
    public function data_user()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $filter    = $this->request->getGet('filter_role') ?? '';
        $data_user = $filter
            ? $this->userModel->where('role', $filter)->orderBy('id', 'DESC')->findAll()
            : $this->userModel->orderBy('id', 'DESC')->findAll();

        return view('admin/data_user', [
            'title' => 'Data Users',
            'data_user' => $data_user,
            'filter'    => $filter
        ]);
    }

    public function reset_password_admin($username)
    {
        // WAJIB TAMBAHKAN INI AGAR HANYA ADMIN YANG BISA AKSES!
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        // Admin mengubah password menjadi NULL (dikosongkan)
        $db->query("UPDATE users SET password = NULL WHERE username = ?", [$username]);

        // Menggunakan redirect ke halaman data_user agar flashdata 'success' terbaca dengan baik
        return redirect()->to('/admin/data_user')->with('success', 'Password user ' . $username . ' berhasil direset. User sekarang bisa membuat password baru.');
    }

    public function tambah_user()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $username = trim($this->request->getPost('username'));
        $role     = $this->request->getPost('role');

        $cek = $this->userModel->getByUsername($username);
        if ($cek) {
            return redirect()->to('/admin/data_user')
                ->with('error', 'Username/NPM/NIDN ini sudah terdaftar!');
        }

        $this->userModel->insert([
            'username' => $username,
            'password' => null,
            'role'     => $role
        ]);

        return redirect()->to('/admin/data_user');
    }

    public function hapus_user()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id = $this->request->getGet('id');
        $this->userModel->delete($id);
        return redirect()->to('/admin/data_user');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA MAHASISWA
    |--------------------------------------------------------------------------
    */
    public function data_mahasiswa()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        return view('admin/data_mahasiswa', [
            'title' => 'Data Mahasiswa',
            'data'  => $this->mahasiswaModel->getAll()
        ]);
    }

    public function tambah_mahasiswa()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $this->mahasiswaModel->insert([
            'npm'      => $this->request->getPost('npm'),
            'nama'     => $this->request->getPost('nama'),
            'angkatan' => $this->request->getPost('angkatan'),
        ]);

        return redirect()->to('/admin/data_mahasiswa')
            ->with('success', 'Data mahasiswa berhasil ditambahkan');
    }

    public function edit_mahasiswa()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm = $this->request->getGet('npm');
        return view('admin/data_mahasiswa_edit', [
            'title' => 'Update Data Mahasiswa',
            'row' => $this->mahasiswaModel->getByNpm($npm)
        ]);
    }

    public function update_mahasiswa()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm = $this->request->getPost('npm');
        $this->mahasiswaModel->update($npm, [
            'nama'     => $this->request->getPost('nama'),
            'angkatan' => $this->request->getPost('angkatan'),
        ]);

        return redirect()->to('/admin/data_mahasiswa')
            ->with('success', 'Data mahasiswa berhasil diperbarui');
    }

    public function hapus_mahasiswa()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm = $this->request->getGet('npm');
        $this->mahasiswaModel->delete($npm);

        return redirect()->to('/admin/data_mahasiswa')
            ->with('success', 'Data mahasiswa berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA DOSEN
    |--------------------------------------------------------------------------
    */
    public function data_dosen()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        return view('admin/data_dosen', [
            'title' => 'Data Dosen',
            'data'  => $this->dosenModel->getAll(),
            'topik' => $this->topikModel->getAll()
        ]);
    }

    public function tambah_dosen()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $this->dosenModel->insert([
            'nidn'            => $this->request->getPost('nidn'),
            'nama'            => $this->request->getPost('nama'),
            'bidang_keahlian' => $this->request->getPost('bidang_keahlian'),
            'kuota_bimbingan' => $this->request->getPost('kuota_bimbingan'),
            'total_bimbingan' => 0
        ]);

        return redirect()->to('/admin/data_dosen')
            ->with('success', 'Data dosen berhasil ditambahkan');
    }

    public function edit_dosen()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $nidn = $this->request->getGet('nidn');
        return view('admin/data_dosen_edit', [
            'title' => 'Update Data Dosen',
            'row'        => $this->dosenModel->getByNidn($nidn),
            'list_topik' => $this->topikModel->getAll()
        ]);
    }

    public function update_dosen()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $nidn = $this->request->getPost('nidn');
        $this->dosenModel->update($nidn, [
            'nama'            => $this->request->getPost('nama'),
            'bidang_keahlian' => $this->request->getPost('bidang_keahlian'),
            'kuota_bimbingan' => $this->request->getPost('kuota_bimbingan'),
        ]);

        return redirect()->to('/admin/data_dosen')
            ->with('success', 'Data dosen berhasil diperbarui');
    }

    public function hapus_dosen()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $nidn = $this->request->getGet('nidn');
        $dosen = $this->dosenModel->getByNidn($nidn);

        if ($dosen['total_bimbingan'] > 0) {
            return redirect()->to('/admin/data_dosen')
                ->with('error', 'Dosen masih memiliki mahasiswa bimbingan');
        }

        $this->dosenModel->delete($nidn);
        return redirect()->to('/admin/data_dosen')
            ->with('success', 'Data dosen berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA TOPIK
    |--------------------------------------------------------------------------
    */
    public function data_topik()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        return view('admin/data_topik', [
            'title' => 'Data Topik',
            'data' => $this->topikModel->getAll()
        ]);
    }

    public function tambah_topik()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $this->topikModel->insert([
            'id_topik'   => $this->request->getPost('id_topik'),
            'nama_topik' => $this->request->getPost('nama_topik'),
        ]);

        return redirect()->to('/admin/data_topik')
            ->with('success', 'Topik skripsi berhasil ditambahkan');
    }

    public function edit_topik()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id = $this->request->getGet('id_topik');
        return view('admin/data_topik_edit', [
            'title' => 'Update Data Topik',
            'row' => $this->topikModel->getById($id)
        ]);
    }

    public function update_topik()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_topik      = $this->request->getPost('id_topik');
        $nama_baru     = trim($this->request->getPost('nama_topik'));
        $topik_lama    = $this->topikModel->getById($id_topik);
        $nama_lama     = trim($topik_lama['nama_topik']);

        $this->topikModel->update($id_topik, ['nama_topik' => $nama_baru]);

        $this->db->query("
            UPDATE dosen SET bidang_keahlian = ?
            WHERE TRIM(bidang_keahlian) = ?
        ", [$nama_baru, $nama_lama]);

        return redirect()->to('/admin/data_topik')
            ->with('success', 'Topik skripsi berhasil diperbarui');
    }

    public function hapus_topik()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id        = $this->request->getGet('id_topik');
        $topik     = $this->topikModel->getById($id);
        $nama_lama = trim($topik['nama_topik']);

        $this->topikModel->delete($id);

        $this->db->query("
            UPDATE dosen SET bidang_keahlian = NULL
            WHERE TRIM(bidang_keahlian) = ?
        ", [$nama_lama]);

        return redirect()->to('/admin/data_topik')
            ->with('success', 'Topik skripsi berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA PENGAJUAN (Review Dosen)
    |--------------------------------------------------------------------------
    */
    public function data_pengajuan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        return view('admin/data_pengajuan', [
            'title' => 'Data Pengajuan',
            'semua_pengajuan' => $this->pengajuanModel->getPengajuanLengkap()
        ]);
    }

    // Tambahkan atau perbarui method ini di Admin.php
    public function tolak_pengajuan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_pengajuan = $this->request->getPost('id_pengajuan');
        $feedback = $this->request->getPost('feedback_admin');

        if (empty($id_pengajuan)) {
            return redirect()->back()->with('error', 'ID tidak ditemukan!');
        }

        // Melakukan update status
        $this->pengajuanModel->update($id_pengajuan, [
            'status' => 'Ditolak Permanen',
            'feedback_admin' => $feedback,
            'tgl_dibatalkan_ditolak' => date('Y-m-d H:i:s') // Pastikan nama kolom di database ini sudah sesuai
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak permanen.');
    }


    /*
    |--------------------------------------------------------------------------
    | PENETAPAN PEMBIMBING
    |--------------------------------------------------------------------------
    */
    public function penetapan_pembimbing()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $dosen = $this->dosenModel->getAll();

        // Ambil data
        $data_pengajuan = $this->pengajuanModel->getSiapDitetapkan();

        // Filter agar "Ditolak Permanen" tidak muncul di daftar ini
        $data_bersih = array_values(array_filter($data_pengajuan, function ($item) {
            return $item['status'] !== 'Ditolak Permanen';
        }));

        return view('admin/penetapan_pembimbing', [
            'title' => 'Penetapan Pembimbing',
            'data'  => $data_bersih, // Gunakan data yang sudah difilter
            'dosen' => $dosen
        ]);
    }

    public function proses_penetapan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_pengajuan = $this->request->getPost('id_pengajuan');
        $pembimbing_1 = $this->request->getPost('pembimbing_1');
        $pembimbing_2 = $this->request->getPost('pembimbing_2');

        // 1. CEK DATA SUDAH ADA?
        $cek_exist = $this->pembimbingModel->where('id_pengajuan', $id_pengajuan)->first();
        if ($cek_exist) {
            return redirect()->back()->with('error', 'Gagal! Data sudah ditetapkan sebelumnya.');
        }

        // ============================================
        // 2. CEK MINIMAL 1 PEMBIMBING DIPILIH
        // ============================================
        if (empty($pembimbing_1) && empty($pembimbing_2)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 pembimbing!');
        }

        // ============================================
        // 3. CEK PEMBIMBING TIDAK SAMA (KALAU KEDUANYA DIPILIH)
        // ============================================
        if (!empty($pembimbing_1) && !empty($pembimbing_2) && $pembimbing_1 == $pembimbing_2) {
            return redirect()->back()->with('error', 'Pembimbing 1 dan 2 tidak boleh sama!');
        }

        // ============================================
        // 4. CEK KUOTA DOSEN (HANYA YANG DIPILIH)
        // ============================================

        // Cek quota pembimbing 1 jika dipilih
        if (!empty($pembimbing_1)) {
            $dosen1 = $this->dosenModel->find($pembimbing_1);
            if ($dosen1) {
                $kuota1 = $dosen1['kuota_bimbingan'] ?? 5;
                $total1 = $dosen1['total_bimbingan'] ?? 0;
                $sisa1 = $kuota1 - $total1;

                if ($sisa1 <= 0) {
                    return redirect()->back()->with('error', 'Kuota Pembimbing 1 (' . $dosen1['nama'] . ') sudah penuh!');
                }
            }
        }

        // Cek quota pembimbing 2 jika dipilih
        if (!empty($pembimbing_2)) {
            $dosen2 = $this->dosenModel->find($pembimbing_2);
            if ($dosen2) {
                $kuota2 = $dosen2['kuota_bimbingan'] ?? 5;
                $total2 = $dosen2['total_bimbingan'] ?? 0;
                $sisa2 = $kuota2 - $total2;

                if ($sisa2 <= 0) {
                    return redirect()->back()->with('error', 'Kuota Pembimbing 2 (' . $dosen2['nama'] . ') sudah penuh!');
                }
            }
        }

        // ============================================
        // 5. PROSES PENYIMPANAN
        // ============================================

        $this->db->transStart();

        try {
            // Upload file PDF
            $file = $this->request->getFile('file_pdf');
            $nama_pdf = '';

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $folder = ROOTPATH . 'public/uploads/';
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }
                $nama_pdf = time() . '_' . $file->getClientName();
                $file->move($folder, $nama_pdf);
            }

            // Update status pengajuan
            $this->pengajuanModel->update($id_pengajuan, [
                'status' => 'Ditetapkan',
                'feedback_admin' => $this->request->getPost('feedback_admin')
            ]);

            // Insert into table pembimbing (izinkan NULL jika tidak dipilih)
            $this->pembimbingModel->insert([
                'id_pengajuan' => $id_pengajuan,
                'pembimbing_1' => !empty($pembimbing_1) ? $pembimbing_1 : null,
                'pembimbing_2' => !empty($pembimbing_2) ? $pembimbing_2 : null,
                'file_pdf' => $nama_pdf,
                'tgl_penetapan' => date('Y-m-d H:i:s'),
                'status_bimbingan' => 'Aktif'
            ]);

            // Update quota jika dipilih
            if (!empty($pembimbing_1)) {
                $this->db->query("UPDATE dosen SET 
                total_bimbingan = total_bimbingan + 1,
                total_p1 = total_p1 + 1 
                WHERE nidn = ?", [$pembimbing_1]);
            }

            if (!empty($pembimbing_2)) {
                $this->db->query("UPDATE dosen SET 
                total_bimbingan = total_bimbingan + 1,
                total_p2 = total_p2 + 1 
                WHERE nidn = ?", [$pembimbing_2]);
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Gagal menyimpan ke database.');
        }

        return redirect()->to('/admin/riwayat_pembimbing')->with('success', 'Penetapan berhasil!');
    }

    /*
    |--------------------------------------------------------------------------
    | RIWAYAT PEMBIMBING
    |--------------------------------------------------------------------------
    */
    public function riwayat_pembimbing()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        return view('admin/riwayat_pembimbing', [
            'title' => 'Riwayat Penetapan Pembimbing',
            'data' => $this->pengajuanModel->getSudahDitetapkan()
        ]);
    }

    public function edit_pembimbing()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id = $this->request->getGet('id');
        return view('admin/edit_pembimbing', [
            'title' => 'Update Penetapan Pembimbing',
            'data'  => $this->pengajuanModel->find($id),
            'dosen' => $this->dosenModel->getAll(),
            'pembimbing' => $this->pembimbingModel->getByPengajuan($id)
        ]);
    }

    public function update_pembimbing()
    {
        // 1. Validasi Hak Akses Admin
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        // 2. Tangkap Inputan ID dan Data Pembimbing dari Form
        $id           = $this->request->getPost('id_pengajuan');
        $pembimbing_1 = $this->request->getPost('pembimbing_1');
        $pembimbing_2 = $this->request->getPost('pembimbing_2');

        if (
            !empty($pembimbing_1) &&
            !empty($pembimbing_2) &&
            $pembimbing_1 == $pembimbing_2
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Pembimbing 1 dan Pembimbing 2 tidak boleh sama!'
                );
        }

        if (
            empty($pembimbing_1) &&
            empty($pembimbing_2)
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Pilih minimal 1 pembimbing!'
                );
        }

        // Ambil data pembimbing saat ini untuk keperluan pengecekan file PDF lama
        $pembimbing = $this->pembimbingModel->getByPengajuan($id);
        $nama_pdf   = $pembimbing['file_pdf'] ?? '';

        // pembimbing lama
        $pembimbing1_lama = $pembimbing['pembimbing_1'] ?? null;
        $pembimbing2_lama = $pembimbing['pembimbing_2'] ?? null;

        // =============================
        // VALIDASI KUOTA PEMBIMBING BARU
        // =============================

        // cek pembimbing 1 baru
        if ($pembimbing_1 && $pembimbing_1 != $pembimbing1_lama) {

            $dosen1 = $this->dosenModel->find($pembimbing_1);

            if ($dosen1) {

                $sisa =
                    ($dosen1['kuota_bimbingan'] ?? 5)
                    -
                    ($dosen1['total_bimbingan'] ?? 0);

                if ($sisa <= 0) {

                    return redirect()
                        ->back()
                        ->with(
                            'error',
                            'Kuota Pembimbing 1 (' .
                                $dosen1['nama'] .
                                ') sudah penuh!'
                        );
                }
            }
        }

        // cek pembimbing 2 baru
        if ($pembimbing_2 && $pembimbing_2 != $pembimbing2_lama) {

            $dosen2 = $this->dosenModel->find($pembimbing_2);

            if ($dosen2) {

                $sisa =
                    ($dosen2['kuota_bimbingan'] ?? 5)
                    -
                    ($dosen2['total_bimbingan'] ?? 0);

                if ($sisa <= 0) {

                    return redirect()
                        ->back()
                        ->with(
                            'error',
                            'Kuota Pembimbing 2 (' .
                                $dosen2['nama'] .
                                ') sudah penuh!'
                        );
                }
            }
        }

        // 3. Proses Upload File PDF SK / Kelulusan Baru (Jika Ada)
        $file = $this->request->getFile('file_pdf');

        if (
            $file &&
            $file->isValid() &&
            !$file->hasMoved() &&
            $file->getName() != ''
        ) {
            // Hapus file fisik lama di folder uploads agar penyimpanan tidak penuh
            if (!empty($nama_pdf)) {
                $path_lama = ROOTPATH . 'public/uploads/' . $nama_pdf;
                if (file_exists($path_lama)) {
                    unlink($path_lama);
                }
            }

            // Pindahkan file PDF baru yang diunggah admin ke folder uploads
            $folder   = ROOTPATH . 'public/uploads/';
            $nama_pdf = time() . '_' . $file->getClientName();
            $file->move($folder, $nama_pdf);
        }

        // Ambil data detail pengajuan judul mahasiswa dari database berdasarkan id_pengajuan
        $dataPengajuan = $this->pengajuanModel->find($id);

        if ($dataPengajuan) {
            // Konversi ID Pembimbing 1 menjadi Nama Dosen Asli
            $dosen1 = $this->dosenModel->find($pembimbing_1);
            $nama_pembimbing_1 = $dosen1 ? $dosen1['nama'] : '-';

            // Konversi ID Pembimbing 2 menjadi Nama Dosen Asli
            $dosen2 = $this->dosenModel->find($pembimbing_2);
            $nama_pembimbing_2 = $dosen2 ? $dosen2['nama'] : '-';

            // Proses menyalin & menduplikasi data ke dalam tabel arsip_skripsi terpusat (Tanpa Kategori)
            // $this->arsipModel->insert([
            //     'nim'                => $dataPengajuan['nim'] ?? $dataPengajuan['nim_mahasiswa'] ?? '-',
            //     'nama_mahasiswa'     => $dataPengajuan['nama'] ?? $dataPengajuan['nama_mahasiswa'] ?? '-',
            //     'judul_skripsi'      => $dataPengajuan['judul'] ?? $dataPengajuan['judul_skripsi'] ?? '-',
            //     'abstrak'            => $dataPengajuan['abstrak'] ?? '-',
            //     // Kolom kata_kunci disisipkan tanda [Sistem] di depannya sebagai pemisah dari data import Excel lama
            //     'kata_kunci'         => '[Sistem] ' . ($dataPengajuan['kata_kunci'] ?? '-'),
            //     'dosen_pembimbing_1' => $nama_pembimbing_1,
            //     'dosen_pembimbing_2' => $nama_pembimbing_2,
            //     'tahun_lulus'        => date('Y'), // Otomatis tercatat lulus pada tahun berjalan sekarang
            //     'file_url'           => $nama_pdf // Nama file PDF yang diupload barusan otomatis terhubung sebagai berkas arsip
            // ]);
        }

        // 4. KODE ASLI: Jalankan update data pembimbing utama pada sistem pengajuan judul bawaan projectmu

        $this->db->transStart();
        // ====================================
        // KEMBALIKAN KUOTA DOSEN LAMA
        // ====================================

        if ($pembimbing1_lama && $pembimbing1_lama != $pembimbing_1) {

            $this->db->query("
        UPDATE dosen
        SET total_bimbingan = total_bimbingan - 1,
            total_p1 = total_p1 - 1
        WHERE nidn = ?
        ", [$pembimbing1_lama]);
        }

        if ($pembimbing2_lama && $pembimbing2_lama != $pembimbing_2) {

            $this->db->query("
        UPDATE dosen
        SET total_bimbingan = total_bimbingan - 1,
            total_p2 = total_p2 - 1
        WHERE nidn = ?
        ", [$pembimbing2_lama]);
        }


        // ====================================
        // TAMBAHKAN KUOTA DOSEN BARU
        // ====================================

        if ($pembimbing_1 && $pembimbing1_lama != $pembimbing_1) {

            $this->db->query("
        UPDATE dosen
        SET total_bimbingan = total_bimbingan + 1,
            total_p1 = total_p1 + 1
        WHERE nidn = ?
        ", [$pembimbing_1]);
        }

        if ($pembimbing_2 && $pembimbing2_lama != $pembimbing_2) {

            $this->db->query("
        UPDATE dosen
        SET total_bimbingan = total_bimbingan + 1,
            total_p2 = total_p2 + 1
        WHERE nidn = ?
        ", [$pembimbing_2]);
        }


        // ====================================
        // UPDATE DATA PEMBIMBING
        // ====================================

        $this->pembimbingModel
            ->where('id_pengajuan', $id)
            ->set([
                'pembimbing_1' => $pembimbing_1,
                'pembimbing_2' => $pembimbing_2,
                'file_pdf'     => $nama_pdf
            ])
            ->update();


        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui data pembimbing.');
        }
        // 5. Kembalikan ke halaman riwayat pembimbing dengan notifikasi sukses
        return redirect()->to('/admin/riwayat_pembimbing')->with(
            'success',
            'Data pembimbing berhasil diperbarui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PENETAPAN PEMBIMBING & KELULUSAN
    |--------------------------------------------------------------------------
    */

    public function batalkan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_pengajuan = $this->request->getPost('id');
        $alasan = $this->request->getPost('alasan') ?? '';

        if (empty($id_pengajuan)) {
            return redirect()->back()->with('error', 'ID tidak boleh kosong!');
        }

        $pembimbing = $this->pembimbingModel->where('id_pengajuan', $id_pengajuan)->first();

        if (!$pembimbing) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $this->db->transStart();

        try {
            // Kembalikan quota dosen
            if (!empty($pembimbing['pembimbing_1'])) {
                $this->db->query("UPDATE dosen SET total_bimbingan = GREATEST(0, total_bimbingan - 1), total_p1 = GREATEST(0, total_p1 - 1) WHERE nidn = ?", [$pembimbing['pembimbing_1']]);
            }
            if (!empty($pembimbing['pembimbing_2'])) {
                $this->db->query("UPDATE dosen SET total_bimbingan = GREATEST(0, total_bimbingan - 1), total_p2 = GREATEST(0, total_p2 - 1) WHERE nidn = ?", [$pembimbing['pembimbing_2']]);
            }

            $this->pembimbingModel->update($pembimbing['id'], ['status_bimbingan' => 'Batal']);

            // ✅ PISAH: feedback_admin (hanya alasan) + tgl_dibatalkan (tanggal sendiri)
            $feedback_text = !empty($alasan) ? $alasan : 'Dibatalkan oleh admin';

            $this->pengajuanModel->update($id_pengajuan, [
                'status' => 'Dibatalkan',
                'kondisi_data' => 'Dibatalkan',
                'feedback_admin' => $feedback_text,
                'tgl_dibatalkan_ditolak' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }

        $this->db->transComplete();
        return redirect()->back()->with('success', 'Penetapan dibatalkan!');
    }



    /*
    |--------------------------------------------------------------------------
    | DAFTAR PEMBATALAN PENETAPAN
    |--------------------------------------------------------------------------
    */

    public function batal_penetapan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        // Panggil fungsi lama Anda
        $batal = $this->pengajuanModel->getDibatalkan();

        // Ambil data yang Ditolak Permanen
        $ditolak = $this->pengajuanModel->where('status', 'Ditolak Permanen')->findAll();

        return view('admin/batal_penetapan', [
            'batal'   => $batal ?? [],
            'ditolak' => $ditolak ?? []
        ]);
    }

    public function hapus_permanen()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id  = $this->request->getGet('id');
        $row = $this->pengajuanModel->find($id);

        if ($row) {
            // 1. Cari file PDF SK bimbingan berjalan, jika ada hapus dari server
            $pembimbing = $this->pembimbingModel->where('id_pengajuan', $id)->first();
            if ($pembimbing && !empty($pembimbing['file_pdf'])) {
                $path = ROOTPATH . 'public/uploads/' . $pembimbing['file_pdf'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // 2. Hapus data bimbingan berjalan di katalog arsip_skripsi jika ada
            $npm_mahasiswa = $row['nim'] ?? $row['npm'] ?? $row['nim_mahasiswa'] ?? '';
            if (!empty($npm_mahasiswa)) {
                $this->arsipModel->where('npm', $npm_mahasiswa)->delete();
            }

            // 3. Bersihkan data pengajuan secara menyeluruh dari database
            $this->reviewModel->where('id_pengajuan', $id)->delete();
            $this->pembimbingModel->where('id_pengajuan', $id)->delete();
            $this->pengajuanModel->delete($id);

            return redirect()->to('/admin/batal_penetapan')->with('success', 'Data pengajuan telah dihapus secara permanen dari sistem.');
        }

        return redirect()->to('/admin/batal_penetapan')->with('error', 'Gagal menghapus data secara permanen.');
    }

    /*
    |--------------------------------------------------------------------------
    | ARSIP SKRIPSI
    |--------------------------------------------------------------------------
    */
    public function arsip_skripsi()
    {
        try {
            $redirect = $this->cekRole();
            if ($redirect) return $redirect;

            $db = \Config\Database::connect();

            // 1. Ambil data Alumni dari tabel arsip (Baris 1 di halaman view-mu)
            $alumni = $db->table('arsip_skripsi')->where('kategori_arsip', 'alumni')->get()->getResultArray();

            // 2. PERBAIKAN BARIS 2: Menggunakan d1.nama dan d2.nama agar sesuai dengan tabel dosen kamu
            $dalam_proses = $this->pengajuanModel
                ->select('
                pengajuan.*, 
                mahasiswa.nama as nama_mahasiswa, 
                topik_skripsi.nama_topik,
                d1.nama AS nama_pembimbing1, 
                d2.nama AS nama_pembimbing2
            ')
                ->join('mahasiswa', 'mahasiswa.npm = pengajuan.npm', 'left')
                ->join('topik_skripsi', 'topik_skripsi.id_topik = pengajuan.id_topik', 'left')
                // Relasi ke tabel pembimbing
                ->join('pembimbing', 'pembimbing.id_pengajuan = pengajuan.id_pengajuan', 'left')
                // Relasi ke tabel dosen menggunakan kolom 'nama'
                ->join('dosen d1', 'd1.nidn = pembimbing.pembimbing_1', 'left')
                ->join('dosen d2', 'd2.nidn = pembimbing.pembimbing_2', 'left')
                ->whereIn('pengajuan.status', ['Ditetapkan', 'Menunggu Sidang'])
                ->where('pengajuan.kondisi_data', 'Aktif')
                ->findAll();

            // 3. Ambil data Lulus Tahun Ini dari tabel arsip (Baris 3 di halaman view-mu)
            $lulus_tahun_ini = $db->table('arsip_skripsi')->where('kategori_arsip', 'lulus_tahun_ini')->get()->getResultArray();

            // Kirim semua variabel ke view
            return view('admin/arsip_skripsi', [
                'title'           => 'Database Arsip Skripsi',
                'alumni'          => $alumni,
                'dalam_proses'    => $dalam_proses,
                'lulus_tahun_ini' => $lulus_tahun_ini,
                // 'keyword'         => $this->request->getGet('keyword') ?? '',
                // 'tahun'           => $this->request->getGet('tahun') ?? '',
                // 'topik'           => $this->request->getGet('topik') ?? ''
            ]);
        } catch (\Exception $e) {
            exit("Error: " . $e->getMessage());
        }
    }
    public function tambah_arsip()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $topik_list = $this->topikModel->getAll();
        $dosen_list = $this->dosenModel->getAll();

        return view('admin/tambah_arsip', [
            'topik_list' => $topik_list,
            'dosen_list' => $dosen_list,
        ]);
    }

    public function simpan_arsip()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $file_url = '';
        $file = $this->request->getFile('file_pdf');
        if ($file && $file->isValid()) {
            $folder   = ROOTPATH . 'public/uploads/';
            if (!is_dir($folder)) mkdir($folder, 0777, true);
            $nama_pdf = time() . '_' . $file->getClientName();
            $file->move($folder, $nama_pdf);
            $file_url = $nama_pdf;
        }

        $this->arsipModel->insert([
            'npm'                    => $this->request->getPost('npm'),
            'nama_mahasiswa'         => $this->request->getPost('nama_mahasiswa'),
            'angkatan'               => $this->request->getPost('angkatan'),
            'judul'                  => $this->request->getPost('judul'),
            'abstrak'                => $this->request->getPost('abstrak'),
            'kata_kunci'             => $this->request->getPost('kata_kunci'),
            'id_topik'               => $this->request->getPost('id_topik'),
            'pembimbing_1'           => $this->request->getPost('pembimbing_1'),
            'pembimbing_2'           => $this->request->getPost('pembimbing_2'),
            'tahun_lulus'            => $this->request->getPost('tahun_lulus'),
            'file_url'               => $file_url,
            'tgl_arsip'              => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/arsip_skripsi');
    }


    // Method lama: Digunakan oleh sistem pengajuan umum
    public function hapus_arsip()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id = $this->request->getGet('id');
        $this->arsipModel->delete($id);
        return redirect()->to('/admin/arsip_skripsi');
    }

    // Method baru: Dibuat khusus untuk fitur Arsip Skripsi
    public function delete_arsip()
    {
        // Ambil ID dari URL (menggunakan ?id=...)
        $id = $this->request->getGet('id');

        // Cek apakah ID ada dan valid
        if (!empty($id)) {
            $db = \Config\Database::connect();

            // Eksekusi hapus langsung ke tabel
            $db->table('arsip_skripsi')->where('id_arsip', $id)->delete();
        }

        // Arahkan kembali ke halaman arsip
        return redirect()->to(base_url('admin/arsip_skripsi'));
    }



    public function auto_arsip()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id         = $this->request->getGet('id');
        $pengajuan  = $this->pengajuanModel->find($id);
        $pembimbing = $this->pembimbingModel->getByPengajuan($id);
        $mahasiswa  = $this->mahasiswaModel->getByNpm($pengajuan['npm']);

        $this->arsipModel->autoArchive($pengajuan, $pembimbing, $mahasiswa);

        $this->pengajuanModel->update($id, ['kondisi_data' => 'Diarsipkan']);

        return redirect()->to('/admin/riwayat_pembimbing')
            ->with('success', 'Data berhasil diarsipkan ke arsip skripsi!');
    }

    /*
    |--------------------------------------------------------------------------
    | Statistik Topik
    |--------------------------------------------------------------------------
    */
public function statistik()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $db = \Config\Database::connect();

        // Mengambil data standar dari model kamu (pasti aman dari error Whoops)
        $statistik_topik  = $this->pengajuanModel->getStatistikTopik();
        $statistik_status = $this->pengajuanModel->getStatistikStatus();

        // 1. SOLUSI AMAN PER BULAN: Mengambil seluruh data pengajuan lewat model
        $semua_pengajuan = $this->pengajuanModel->findAll();
        $data_bulanan = array_fill(1, 12, 0); // Wadah bulan Jan - Des
        $tahun_sekarang = date('Y');

        foreach ($semua_pengajuan as $p) {
            // Kita cari kolom tanggal secara otomatis di dalam array database kamu
            $tanggal = $p['created_at'] ?? $p['tanggal'] ?? $p['tanggal_pengajuan'] ?? null;
            
            if ($tanggal) {
                $time = strtotime($tanggal);
                $tahun_data = date('Y', $time);
                $bulan_data = (int)date('m', $time);

                // Jika datanya ada di tahun berjalan (2026), masukkan ke dalam grafik
                if ($tahun_data == $tahun_sekarang) {
                    $data_bulanan[$bulan_data]++;
                }
            }
        }

        // 2. QUERY ARSIP: Menggunakan klausa standar (tanpa mencantumkan group by ganda yang sensitif di beberapa versi MySQL)
        $statistik_arsip = $db->query("
            SELECT
                topik_skripsi.nama_topik,
                COUNT(arsip_skripsi.id_arsip) as total
            FROM topik_skripsi
            LEFT JOIN arsip_skripsi ON arsip_skripsi.id_topik = topik_skripsi.id_topik
            GROUP BY topik_skripsi.id_topik
            ORDER BY total DESC
        ")->getResultArray();

        return view('admin/statistik', [
            'title'            => 'Laporan & Statistik',
            'statistik_topik'  => $statistik_topik,
            'statistik_status' => $statistik_status,
            'statistik_bulanan'=> array_values($data_bulanan),
            'statistik_arsip'  => $statistik_arsip,
        ]);
    }

    public function import_excel()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        return view('admin/import_excel');
    }

    public function proses_import_mahasiswa()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $file = $this->request->getFile('file_excel');
        if (!$file->isValid()) {
            return redirect()->to('/admin/import_excel')->with('error', 'File tidak valid!');
        }

        $folder = ROOTPATH . 'writable/uploads/';
        if (!is_dir($folder)) mkdir($folder, 0777, true);
        $nama   = time() . '_' . $file->getClientName();
        $file->move($folder, $nama);
        $path   = $folder . $nama;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray();

        $header_row = array_map('trim', $rows[0]);

        // Kolom Wajib Mahasiswa
        $kolom_wajib = ['NPM', 'Nama', 'Angkatan'];

        $kurang = [];
        foreach ($kolom_wajib as $k) {
            if (!in_array($k, $header_row)) $kurang[] = $k;
        }

        if (!empty($kurang)) {
            unlink($path);
            // DISESUAIKAN: Redirect kembali ke /admin/import_excel sesuai routemu
            return redirect()->to('/admin/import_excel')->with('error', 'Format Excel Mahasiswa kurang! Kolom wajib belum ada: ' . implode(', ', $kurang));
        }

        $map = [];
        foreach ($header_row as $i => $h) {
            if (in_array($h, $kolom_wajib)) $map[$h] = $i;
        }

        $berhasil = 0;
        $gagal = 0;
        foreach (array_slice($rows, 1) as $row) {
            $npm  = trim($row[$map['NPM']] ?? '');
            $nama_mhs = trim($row[$map['Nama']] ?? '');

            if (empty($npm) || empty($nama_mhs)) {
                $gagal++;
                continue;
            }

            // DISESUAIKAN: Sekarang resmi menggunakan $this->mahasiswaModel karena sudah terdaftar di __construct
            $this->mahasiswaModel->insert([
                'npm'      => $npm,
                'nama'     => $nama_mhs,
                'angkatan' => trim($row[$map['Angkatan']] ?? '')
            ]);
            $berhasil++;
        }
        unlink($path);
        // DISESUAIKAN: Redirect kembali ke /admin/import_excel
        return redirect()->to('/admin/import_excel')->with('success', "Import Mahasiswa selesai! Berhasil: $berhasil, Gagal: $gagal");
    }

    public function proses_import_dosen()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $file = $this->request->getFile('file_excel');
        if (!$file->isValid()) {
            return redirect()->to('/admin/import_excel')->with('error', 'File tidak valid!');
        }

        $folder = ROOTPATH . 'writable/uploads/';
        if (!is_dir($folder)) mkdir($folder, 0777, true);
        $nama   = time() . '_' . $file->getClientName();
        $file->move($folder, $nama);
        $path   = $folder . $nama;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray();

        $header_row = array_map('trim', $rows[0]);

        // Kolom Wajib Dosen
        $kolom_wajib = ['NIDN', 'Nama', 'Bidang Keahlian', 'Kuota Bimbingan'];

        $kurang = [];
        foreach ($kolom_wajib as $k) {
            if (!in_array($k, $header_row)) $kurang[] = $k;
        }

        if (!empty($kurang)) {
            unlink($path);
            // DISESUAIKAN: Redirect kembali ke /admin/import_excel sesuai routemu
            return redirect()->to('/admin/import_excel')->with('error', 'Format Excel Dosen kurang! Kolom wajib belum ada: ' . implode(', ', $kurang));
        }

        $map = [];
        foreach ($header_row as $i => $h) {
            if (in_array($h, $kolom_wajib)) $map[$h] = $i;
        }

        $berhasil = 0;
        $gagal = 0;
        foreach (array_slice($rows, 1) as $row) {
            $nidn = trim($row[$map['NIDN']] ?? '');
            $nama_dosen = trim($row[$map['Nama']] ?? '');

            if (empty($nidn) || empty($nama_dosen)) {
                $gagal++;
                continue;
            }

            $this->dosenModel->insert([
                'nidn'             => $nidn,
                'nama'             => $nama_dosen,
                'bidang_keahlian'  => trim($row[$map['Bidang Keahlian']] ?? ''),
                'kuota_bimbingan'  => trim($row[$map['Kuota Bimbingan']] ?? 0)
            ]);
            $berhasil++;
        }
        unlink($path);
        // DISESUAIKAN: Redirect kembali ke /admin/import_excel
        return redirect()->to('/admin/import_excel')->with('success', "Import Dosen selesai! Berhasil: $berhasil, Gagal: $gagal");
    }

    public function proses_import_arsip()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $file = $this->request->getFile('file_excel');
        if (!$file->isValid()) {
            return redirect()->to('/admin/import_excel')->with('error', 'File tidak valid!');
        }

        $folder = ROOTPATH . 'writable/uploads/';
        if (!is_dir($folder)) mkdir($folder, 0777, true);
        $nama   = time() . '_' . $file->getClientName();
        $file->move($folder, $nama);
        $path   = $folder . $nama;

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray();

        $header_row = array_map('trim', $rows[0]);

        // Kolom Wajib Arsip
        $kolom_wajib = [
            'NPM',
            'Nama Mahasiswa',
            'Angkatan',
            'Judul',
            'Abstrak',
            'Kata Kunci',
            'ID Topik / Nama Topik',
            'NIDN / Nama Pembimbing 1',
            'NIDN / Nama Pembimbing 2',
            'Tahun Lulus',
            'Link File Skripsi (URL)'
        ];

        $kurang = [];
        foreach ($kolom_wajib as $k) {
            if (!in_array($k, $header_row)) $kurang[] = $k;
        }

        if (!empty($kurang)) {
            unlink($path);
            return redirect()->to('/admin/import_excel')->with('error', 'Format Excel Arsip kurang! Kolom wajib belum ada: ' . implode(', ', $kurang));
        }

        $map = [];
        foreach ($header_row as $i => $h) {
            if (in_array($h, $kolom_wajib)) $map[$h] = $i;
        }

        $berhasil = 0;
        $gagal = 0;
        foreach (array_slice($rows, 1) as $row) {
            $npm         = trim($row[$map['NPM']] ?? '');
            $judul       = trim($row[$map['Judul']] ?? '');

            if (empty($npm) || empty($judul)) {
                $gagal++;
                continue;
            }

            $topik_raw   = trim($row[$map['ID Topik / Nama Topik']] ?? '');
            $p1_raw      = trim($row[$map['NIDN / Nama Pembimbing 1']] ?? '');
            $p2_raw      = trim($row[$map['NIDN / Nama Pembimbing 2']] ?? '');

            // Logika Cari ID Topik, Pembimbing 1, Pembimbing 2 (Sama seperti kode sebelumnya)
            $id_topik = null;
            $nama_topik_arsip = $topik_raw;
            if (!empty($topik_raw)) {
                $cek_topik = $this->db->query("SELECT id_topik, nama_topik FROM topik_skripsi WHERE id_topik = ? OR nama_topik = ?", [$topik_raw, $topik_raw])->getRowArray();
                if ($cek_topik) {
                    $id_topik = $cek_topik['id_topik'];
                    $nama_topik_arsip = $cek_topik['nama_topik'];
                }
            }

            $pembimbing_1 = null;
            $nama_pembimbing1_arsip = $p1_raw;
            if (!empty($p1_raw)) {
                $cek_dsn1 = $this->db->query("SELECT nidn, nama FROM dosen WHERE nama = ? OR nidn = ?", [$p1_raw, $p1_raw])->getRowArray();
                if ($cek_dsn1) {
                    $pembimbing_1 = $cek_dsn1['nidn'];
                    $nama_pembimbing1_arsip = $cek_dsn1['nama'];
                }
            }

            $pembimbing_2 = null;
            $nama_pembimbing2_arsip = $p2_raw;
            if (!empty($p2_raw)) {
                $cek_dsn2 = $this->db->query("SELECT nidn, nama FROM dosen WHERE nama = ? OR nidn = ?", [$p2_raw, $p2_raw])->getRowArray();
                if ($cek_dsn2) {
                    $pembimbing_2 = $cek_dsn2['nidn'];
                    $nama_pembimbing2_arsip = $cek_dsn2['nama'];
                }
            }

            $this->arsipModel->insert([
                'npm'                    => $npm,
                'nama_mahasiswa'         => trim($row[$map['Nama Mahasiswa']] ?? ''),
                'angkatan'               => trim($row[$map['Angkatan']] ?? ''),
                'judul'                  => $judul,
                'abstrak'                => trim($row[$map['Abstrak']] ?? ''),
                'kata_kunci'             => trim($row[$map['Kata Kunci']] ?? ''),
                'id_topik'               => $id_topik,
                'nama_topik_arsip'       => $nama_topik_arsip,
                'pembimbing_1'           => $pembimbing_1,
                'pembimbing_2'           => $pembimbing_2,
                'nama_pembimbing1_arsip' => $nama_pembimbing1_arsip,
                'nama_pembimbing2_arsip' => $nama_pembimbing2_arsip,
                'tahun_lulus'            => trim($row[$map['Tahun Lulus']] ?? date('Y')),
                'file_url'               => trim($row[$map['Link File Skripsi (URL)']] ?? ''),
                'tgl_arsip'              => date('Y-m-d H:i:s')
            ]);
            $berhasil++;
        }
        unlink($path);
        return redirect()->to('/admin/import_excel')->with('success', "Import arsip selesai! Berhasil: $berhasil, Gagal: $gagal");
    }


    // 1. Tombol Download khusus Data Lama (Hasil Import Excel)
    public function export_arsip_import()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        // Ambil data yang kata_kunci-nya TIDAK mengandung kata '[Sistem]'
        $dataArsip = $this->arsipModel->notLike('kata_kunci', '[Sistem]')->findAll();

        $filename = "Arsip_Skripsi_Lama_Import_" . date('Y-m-d') . ".xls";
        $this->proses_download_excel($dataArsip, $filename);
    }

    // 2. Tombol Download khusus Data Baru (Hasil Kelulusan Mahasiswa/Auto-Archiving)
    public function export_arsip_sistem()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        // Ambil data yang kata_kunci-nya HANYA mengandung kata '[Sistem]'
        $dataArsip = $this->arsipModel->table('arsip_skripsi')
            ->like('kata_kunci', '[Sistem]')
            ->findAll();

        $filename = "Arsip_Skripsi_Mahasiswa_Lulus_" . date('Y-m-d') . ".xls";
        $this->proses_download_excel($dataArsip, $filename);
    }

    // Fungsi pembantu (helper) agar tidak menulis kode tabel berulang-ulang
    private function proses_download_excel($dataArsip, $filename)
    {
        $filename = str_replace('.xls', '.csv', $filename);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header kolom disesuaikan (Kategori Bidang Resmi Dihapus)
        fputcsv($output, [
            'NPM',
            'Nama Mahasiswa',
            'Angkatan',
            'Judul Skripsi',
            'Topik',
            'Kata Kunci',
            'Pembimbing 1',
            'Pembimbing 2',
            'Tahun Lulus',
            'Abstrak',
            'File URL'
        ], ';');

        if (!empty($dataArsip)) {
            foreach ($dataArsip as $row) {
                $kata_kunci_bersih = str_replace('[Sistem] ', '', $row['kata_kunci'] ?? '');

                fputcsv($output, [
                    $row['npm'] ?? '',
                    $row['nama_mahasiswa'] ?? '',
                    $row['angkatan'] ?? '-',
                    $row['judul'] ?? '',
                    $row['nama_topik'] ?? '-',
                    $kata_kunci_bersih,
                    $row['nama_pembimbing1'] ?? '-',
                    $row['nama_pembimbing2'] ?? '-',
                    $row['tahun_lulus'] ?? '',
                    $row['abstrak'] ?? '-',
                    $row['file_url'] ?? ''
                ], ';');
            }
        } else {
            fputcsv($output, ['Tidak ada data arsip di kategori ini'], ';');
        }

        fclose($output);
        exit;
    }


    public function download_template_mahasiswa()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $headers = ['NPM', 'Nama', 'Angkatan'];
        $kolom   = ['A', 'B', 'C'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($kolom[$i] . '1', $header);
        }

        $contoh = ['2021001', 'Contoh Mahasiswa', '2021'];
        foreach ($contoh as $i => $val) {
            $sheet->setCellValue($kolom[$i] . '2', $val);
        }

        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_mahasiswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function download_template_dosen()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $headers = ['NIDN', 'Nama', 'Bidang Keahlian', 'Kuota Bimbingan'];
        $kolom   = ['A', 'B', 'C', 'D'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($kolom[$i] . '1', $header);
        }

        $contoh = ['D003', 'Contoh Dosen', 'Kecerdasan Buatan', '5'];
        foreach ($contoh as $i => $val) {
            $sheet->setCellValue($kolom[$i] . '2', $val);
        }

        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_dosen.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }


    public function download_template_arsip()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Kategori Bidang Dihapus dari Header Template Excel
        $headers = [
            'NPM',
            'Nama Mahasiswa',
            'Angkatan',
            'Judul',
            'Abstrak',
            'Kata Kunci',
            'ID Topik / Nama Topik',
            'NIDN / Nama Pembimbing 1',
            'NIDN / Nama Pembimbing 2',
            'Tahun Lulus',
            'Link File Skripsi (URL)'
        ];

        $kolom = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($kolom[$i] . '1', $header);
        }

        // Contoh data disesuaikan dengan hilangnya Kategori Bidang
        $contoh = [
            '24111100004',
            'Contoh Mahasiswa',
            '2022',
            'Judul Skripsi Contoh',
            'Abstrak singkat skripsi',
            'AI, Machine Learning',
            'Kecerdasan Buatan',
            'D001',
            'D002',
            '2024',
            'https://drive.google.com/file/d/contoh_link'
        ];

        foreach ($contoh as $i => $val) {
            $sheet->setCellValue($kolom[$i] . '2', $val);
        }

        $writer   = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_arsip_skripsi.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function kelola_sidang()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $data['mahasiswa_sidang'] = $this->pengajuanModel
            ->select('pengajuan.*, 
                  mahasiswa.nama as nama_mahasiswa, 
                  mahasiswa.angkatan, 
                  topik_skripsi.nama_topik,
                  p1.nama as nama_pembimbing1,
                  p2.nama as nama_pembimbing2') // Ambil nama asli dosen dari alias p1 dan p2
            ->join('mahasiswa', 'mahasiswa.npm = pengajuan.npm', 'left')
            ->join('topik_skripsi', 'topik_skripsi.id_topik = pengajuan.id_topik', 'left')

            // 1. Hubungkan dulu tabel pengajuan ke tabel pembimbing
            ->join('pembimbing', 'pembimbing.id_pengajuan = pengajuan.id_pengajuan', 'left')

            // 2. Hubungkan data pembimbing_1 ke tabel dosen (kita sebut p1)
            ->join('dosen as p1', 'p1.nidn = pembimbing.pembimbing_1', 'left')

            // 3. Hubungkan data pembimbing_2 ke tabel dosen (kita sebut p2)
            ->join('dosen as p2', 'p2.nidn = pembimbing.pembimbing_2', 'left')

            ->where('pengajuan.status', 'Menunggu Sidang')
            ->findAll();

        return view('admin/kelola_sidang', $data);
    }

    public function proses_kelulusan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        // 1. Ambil data dari form kelola_sidang
        $id_pengajuan = $this->request->getPost('id_pengajuan');
        $nilai = $this->request->getPost('nilai_skripsi');

        // 2. Ambil data lengkap mahasiswa & pembimbingnya sebelum status diubah
        $dataPengajuan = $this->pengajuanModel
            ->select('pengajuan.*, pembimbing.pembimbing_1, pembimbing.pembimbing_2')
            ->join('pembimbing', 'pembimbing.id_pengajuan = pengajuan.id_pengajuan', 'left')
            ->where('pengajuan.id_pengajuan', $id_pengajuan)
            ->first();

        if ($dataPengajuan) {

            // 3. Update status mahasiswa di tabel pengajuan menjadi 'Lulus'
            // (Otomatis membuat mahasiswa hilang dari Kelola Sidang & Baris 2 Arsip)
            $this->pengajuanModel->update($id_pengajuan, [
                'status' => 'Lulus'
            ]);

            // 4. Masukkan data ke dalam tabel arsip_skripsi (Baris 3)
            $this->db->table('arsip_skripsi')->insert([
                'npm'             => $dataPengajuan['npm'],
                'judul_skripsi'   => $dataPengajuan['judul'], // Sesuai dengan $row['judul'] di view
                'pembimbing_1'    => $dataPengajuan['pembimbing_1'] ?? '-',
                'pembimbing_2'    => $dataPengajuan['pembimbing_2'] ?? '-',
                'kategori_arsip'  => 'lulus_tahun_ini', // Menandakan masuk ke Baris 3
                'nilai_skripsi'   => $nilai, // Kolom baru phpMyAdmin kamu
                'file_skripsi'    => $dataPengajuan['link_draf_skripsi'], // Sesuai dengan $row['link_draf_skripsi'] di view
                'created_at'      => date('Y-m-d H:i:s')
            ]);

            // Kembali ke halaman kelola sidang dengan flashdata 'pesan' sesuai setelan view kamu
            return redirect()->to('/admin/kelola_sidang')->with('pesan', 'Mahasiswa berhasil dinyatakan Lulus dan data telah diarsipkan.');
        }

        return redirect()->to('/admin/kelola_sidang')->with('pesan', 'Error: Data pengajuan tidak ditemukan.');
    }


    public function batal_kelulusan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_arsip = $this->request->getGet('id_arsip');
        $id_pengajuan = $this->request->getGet('id_pengajuan');

        if ($id_arsip && $id_pengajuan) {

            // 1. Kembalikan status mahasiswa di tabel pengajuan menjadi 'Menunggu Sidang'
            $this->pengajuanModel->update($id_pengajuan, [
                'status' => 'Menunggu Sidang'
            ]);

            // 2. Hapus data kelulusan dari tabel arsip_skripsi
            $this->db->table('arsip_skripsi')->where('id_arsip', $id_arsip)->delete();

            // Kembali dengan pesan peringatan sukses di bagian atas arsip_skripsi
            return redirect()->to('/admin/arsip_skripsi')->with('pesan', '🔄 Kelulusan berhasil dibatalkan! Data mahasiswa telah sukses dikembalikan ke halaman Kelola Sidang.');
        }

        return redirect()->to('/admin/arsip_skripsi')->with('pesan', '❌ Gagal memproses: Data tidak lengkap.');
    }

    public function delete_arsip_sistem()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id = $this->request->getGet('id');

        if ($id) {
            // Hapus data secara permanen dari tabel arsip_skripsi
            $this->db->table('arsip_skripsi')->where('id_arsip', $id)->delete();

            // Kembali dengan pesan peringatan sukses di bagian atas arsip_skripsi
            return redirect()->to('/admin/arsip_skripsi')->with('pesan', '❌ Data arsip kelulusan sistem telah dihapus secara PERMANEN dari database.');
        }

        return redirect()->to('/admin/arsip_skripsi')->with('pesan', '❌ Gagal memproses: ID tidak ditemukan.');
    }



    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
