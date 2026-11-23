<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\TopikModel;
use App\Models\PengajuanModel;
use App\Models\ReviewModel;

class Mahasiswa extends BaseController
{
    protected $mahasiswaModel;
    protected $dosenModel;
    protected $topikModel;
    protected $pengajuanModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->dosenModel     = new DosenModel();
        $this->topikModel     = new TopikModel();
        $this->pengajuanModel = new PengajuanModel();
        $this->reviewModel    = new ReviewModel();
    }

    private function cekRole()
    {
        if (session()->get('role') != 'mahasiswa') {
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

        $npm = session()->get('username');

        $total = $this->pengajuanModel
            ->where('npm', $npm)
            ->countAllResults();

        return view('mahasiswa/dashboard', [
            'title'           => 'Dashboard Mahasiswa',
            'total_pengajuan' => $total
        ]);

        // return view('layout/template_mahasiswa', [
        //     'total_pengajuan' => $total
        // ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Form Pengajuan
    |--------------------------------------------------------------------------
    */
    public function pengajuan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm         = session()->get('username');
        $dosen       = $this->dosenModel->getDosenTersedia();
        $topik       = $this->topikModel->getAll();
        $masih_aktif = $this->pengajuanModel->getPengajuanAktifByNpm($npm);

        return view('mahasiswa/pengajuan', [
            'title'       => 'Form Pengajuan Skripsi',
            'dosen'       => $dosen,
            'topik'       => $topik,
            'masih_aktif' => $masih_aktif,
            'pesan'       => session()->getFlashdata('pesan')
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Proses Submit Pengajuan
    |--------------------------------------------------------------------------
    */
    public function proses_pengajuan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm = session()->get('username');
        $masih_aktif = $this->pengajuanModel->getPengajuanAktifByNpm($npm);

        if ($masih_aktif) {
            session()->setFlashdata('pesan', 'Masih ada pengajuan yang sedang berjalan');
            return redirect()->to('/mahasiswa/pengajuan');
        }

        $nama        = $this->request->getPost('nama');
        $angkatan    = $this->request->getPost('angkatan');
        $judul       = $this->request->getPost('judul');
        $abstrak     = $this->request->getPost('abstrak');
        $id_topik    = $this->request->getPost('id_topik');
        $calon_dosen = $this->request->getPost('calon_dosen');
        $tanggal     = date('Y-m-d H:i:s');

        // Cek kuota dosen
        $dosen = $this->dosenModel->getByNidn($calon_dosen);
        if ($dosen['total_bimbingan'] >= $dosen['kuota_bimbingan']) {
            session()->setFlashdata('pesan', 'Kuota dosen penuh');
            return redirect()->to('/mahasiswa/pengajuan');
        }

        // Cek & insert mahasiswa jika belum ada
        $mahasiswa = $this->mahasiswaModel->getByNpm($npm);
        if (!$mahasiswa) {
            $this->mahasiswaModel->insert([
                'npm'      => $npm,
                'nama'     => $nama,
                'angkatan' => $angkatan
            ]);
        }

        // Insert pengajuan
        $this->pengajuanModel->insert([
            'npm'           => $npm,
            'judul'         => $judul,
            'abstrak'       => $abstrak,
            'id_topik'      => $id_topik,
            'calon_dosen'   => $calon_dosen,
            'status'        => 'Pending',
            'kondisi_data'  => 'Aktif',
            'tgl_pengajuan' => $tanggal
        ]);

        $id_pengajuan = $this->pengajuanModel->getInsertID();

        // Insert review awal dengan status Pending
        $this->reviewModel->insert([
            'id_pengajuan' => $id_pengajuan,
            'nidn'         => $calon_dosen,
            'feedback'     => '',
            'status'       => 'Pending',
            'tgl_review'   => null
        ]);

        session()->setFlashdata('pesan', 'Pengajuan berhasil dikirim');
        return redirect()->to('/mahasiswa/status');
    }

    /*
    |--------------------------------------------------------------------------
    | Status Pengajuan
    |--------------------------------------------------------------------------
    */
    public function status()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm = session()->get('username'); // Sesuaikan nama session NPM kamu

        // PENTING: Gunakan getByNpm, JANGAN gunakan getPengajuanAktifByNpm
        // Agar status "Dibatalkan" tetap terseret ke halaman status
        $data_pengajuan = $this->pengajuanModel->getByNpm($npm);

        // (Logika sisa_detik untuk tombol PDF tetap dipertahankan jika ada)
        $sisa_detik = 0;
        if ($data_pengajuan && !empty($data_pengajuan['tgl_penetapan'])) {
            $waktu_buka = strtotime($data_pengajuan['tgl_penetapan']) + (36 * 3600);
            $sisa_detik = max(0, $waktu_buka - time());
        }

        return view('mahasiswa/status', [
            'title'       => 'Status Pengajuan Skripsi',
            'data'       => $data_pengajuan,
            'sisa_detik' => $sisa_detik
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Riwayat Pengajuan
    |--------------------------------------------------------------------------
    |*/
    public function riwayat()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm = session()->get('username');

        // Mengambil semua riwayat (Pastikan method getRiwayatByNpm di Model 
        // memang mengambil semua status termasuk 'Ditolak Permanen')
        $semua_riwayat = $this->pengajuanModel->getRiwayatByNpm($npm);

        $filter = $this->request->getGet('filter_pengajuan');
        $riwayat = $semua_riwayat;

        if (!empty($filter) && $filter !== 'semua') {
            $riwayat = array_filter($semua_riwayat, function ($r) use ($filter) {
                return $r['id_pengajuan'] == $filter;
            });
        }

        return view('mahasiswa/riwayat', [
            'title'       => 'Riwayat Pengajuan Skripsi',
            'riwayat'         => $riwayat,
            'semua_riwayat'   => $semua_riwayat,
            'filter_terpilih' => $filter
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Hapus Pengajuan
    |--------------------------------------------------------------------------
    */
    public function hapus()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id  = $this->request->getGet('id');
        $npm = session()->get('username');

        $cek = $this->pengajuanModel
            ->where('id_pengajuan', $id)
            ->where('npm', $npm)
            ->first();

        if (!$cek) {
            return redirect()->to('/mahasiswa/status');
        }

        $review = $this->reviewModel->getByPengajuan($id);

        // Hanya bisa hapus jika masih Pending
        if ($review && $review['status'] != 'Pending') {
            return redirect()->to('/mahasiswa/status');
        }

        // Hapus review dulu lalu pengajuan
        $this->reviewModel->where('id_pengajuan', $id)->delete();
        $this->pengajuanModel->delete($id);

        return redirect()->to('/mahasiswa/status');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Pengajuan
    |--------------------------------------------------------------------------
    */
    public function edit()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id  = $this->request->getGet('id');
        $npm = session()->get('username');

        $cek = $this->pengajuanModel
            ->where('id_pengajuan', $id)
            ->where('npm', $npm)
            ->first();

        if (!$cek) {
            return redirect()->to('/mahasiswa/status');
        }

        $review = $this->reviewModel->getByPengajuan($id);

        // Bisa edit jika Pending atau Direvisi
        if ($review && $review['status'] != 'Pending' && $review['status'] != 'Direvisi') {
            return redirect()->to('/mahasiswa/status');
        }

        $topik = $this->topikModel->getAll();
        $dosen = $this->dosenModel->getDosenTersedia();

        return view('mahasiswa/edit_pengajuan', [
            'data'   => $cek,
            'review' => $review,
            'topik'  => $topik,
            'dosen'  => $dosen,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Proses Edit Pengajuan
    |--------------------------------------------------------------------------
    */
    public function proses_edit()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id = $this->request->getPost('id_pengajuan');
        $npm = session()->get('username');

        $cek = $this->pengajuanModel
            ->where('id_pengajuan', $id)
            ->where('npm', $npm)
            ->first();

        if (!$cek) {
            return redirect()->to('/mahasiswa/status');
        }

        $review = $this->reviewModel->getByPengajuan($id);

        // Bisa edit jika Pending atau Direvisi
        if ($review && $review['status'] != 'Pending' && $review['status'] != 'Direvisi') {
            return redirect()->to('/mahasiswa/status');
        }

        $calon_dosen_baru = $this->request->getPost('calon_dosen');

        // Update pengajuan
        $this->pengajuanModel->update($id, [
            'judul'        => $this->request->getPost('judul'),
            'abstrak'      => $this->request->getPost('abstrak'),
            'id_topik'     => $this->request->getPost('id_topik'),
            'calon_dosen'  => $calon_dosen_baru,
            'status'       => 'Pending',
            'kondisi_data' => 'Aktif' // <--- PERBAIKAN: Wajib ditambahkan agar kembali terbaca sistem
        ]);

        // Reset review ke Pending setelah direvisi
        if ($review) {
            $this->reviewModel->update($review['id_review'], [
                'nidn'           => $calon_dosen_baru,
                'status'         => 'Pending',
                'feedback_lama'  => $review['feedback'], // simpan feedback lama
                'feedback'       => '',
                'tgl_review'     => null,
                'sudah_direvisi' => $review['status'] == 'Direvisi' ? 1 : 0
            ]);
        }

        session()->setFlashdata('pesan', 'Pengajuan berhasil diupdate, menunggu review ulang dari dosen');
        return redirect()->to('/mahasiswa/status');
    }

    /*
    |--------------------------------------------------------------------------
    | Mengecek Duplikasi
    |--------------------------------------------------------------------------
    */
    public function cek_duplikasi()
    {
        $judul        = $this->request->getPost('judul');
        $id_pengajuan = $this->request->getPost('id_pengajuan') ?? null;

        $arsipModel = new \App\Models\ArsipModel();
        $duplikat   = $arsipModel->cekDuplikasi($judul, $id_pengajuan);

        return $this->response->setJSON([
            'ada'  => count($duplikat) > 0,
            'data' => $duplikat
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Proses Daftar Sidang Skripsi
    |--------------------------------------------------------------------------
    */
    public function proses_daftar_sidang()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_pengajuan = $this->request->getPost('id_pengajuan');
        $link_draf    = $this->request->getPost('link_draf_skripsi');

        // Validasi input: Pastikan format yang diketik mahasiswa adalah URL link yang sah 
        if (filter_var($link_draf, FILTER_VALIDATE_URL) === false) {
            return redirect()->to('/mahasiswa/logbook')->with('error', '⚠️ Gagal: Format Link Google Drive tidak valid!');
        }

        // Update status pengajuan menjadi 'Menunggu Sidang' dan simpan link drive drafnya 
        $this->pengajuanModel->update($id_pengajuan, [
            'status'            => 'Menunggu Sidang',
            'link_draf_skripsi' => $link_draf
        ]);

        return redirect()->to('/mahasiswa/logbook')->with('success', '🎉 Pendaftaran sidang berhasil! Menunggu Admin menentukan jadwal ujian.');
    }
    // ==========================================
    // FITUR LOGBOOK MAHASISWA
    // ==========================================
    public function logbook()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $npm = session()->get('username');
        $logbookModel = new \App\Models\LogbookModel();
        $pengajuanModel = new \App\Models\PengajuanModel();

        // Angka target bisa kamu ubah di sini saja nanti
        $target_acc = 5;

        $riwayat = $pengajuanModel->getRiwayatByNpm($npm);
        $pengajuanAktif = null;

        foreach ($riwayat as $r) {
            if (in_array($r['status'], ['Ditetapkan', 'Menunggu Sidang', 'Lulus'])) {
                $pengajuanAktif = $r;
                break;
            }
        }

        $data_logbook = $logbookModel->getLogbookMahasiswa($npm);
        $jumlah_disetujui = 0;

        if ($pengajuanAktif) {
            $jumlah_disetujui = $logbookModel->where([
                'id_pengajuan'    => $pengajuanAktif['id_pengajuan'],
                'status_validasi' => 'Disetujui'
            ])->countAllResults();
        }

        return view('mahasiswa/logbook', [
            'title'            => 'Logbook Bimbingan Digital',
            'pengajuan'        => $pengajuanAktif,
            'logbook'          => $data_logbook,
            'jumlah_disetujui' => $jumlah_disetujui
        ]);
    }

    public function simpan_logbook()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_pengajuan = $this->request->getPost('id_pengajuan');
        $pengajuanModel = new \App\Models\PengajuanModel();

        // --- KUNCI PENGAMAN (VALIDASI BACKEND) ---
        // Pastikan status beneran masih "Ditetapkan". Kalau sudah "Menunggu Sidang", tolak!
        $data_cek = $pengajuanModel->find($id_pengajuan);

        if (!$data_cek || $data_cek['status'] != 'Ditetapkan') {
            return redirect()->to('/mahasiswa/logbook')->with('error', 'Gagal! Logbook dikunci karena Anda sudah mendaftar sidang atau status belum ditetapkan.');
        }
        // ---------------------------------

        $logbookModel = new \App\Models\LogbookModel();
        $logbookModel->insert([
            'id_pengajuan'      => $id_pengajuan,
            'npm'               => session()->get('username'),
            'nidn_dosen'        => $this->request->getPost('nidn_dosen'),
            'tanggal_bimbingan' => $this->request->getPost('tanggal_bimbingan'),
            'materi_bimbingan'  => $this->request->getPost('materi_bimbingan'),
            'file_mahasiswa_url' => $this->request->getPost('file_mahasiswa_url'),
            'status_validasi'   => 'Menunggu'
        ]);

        return redirect()->to('/mahasiswa/logbook')->with('success', 'Catatan logbook berhasil dikirim ke dosen!');
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
