<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\PengajuanModel;
use App\Models\ReviewModel;

class Dosen extends BaseController
{
    // 1. TAMBAHKAN BARIS INI UNTUK MENGHILANGKAN ERROR/WARNING
    protected $db;
    protected $dosenModel;
    protected $pengajuanModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->db             = \Config\Database::connect();
        $this->dosenModel     = new DosenModel();
        $this->pengajuanModel = new PengajuanModel();
        $this->reviewModel    = new ReviewModel();
    }

    private function cekRole()
    {
        if (session()->get('role') != 'dosen') {
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

        $nidn = session()->get('username');

        $total = $this->pengajuanModel
            ->where('calon_dosen', $nidn)
            ->countAllResults();

        $pending = $this->db->query("
            SELECT COUNT(*) as total FROM pengajuan
            LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
            WHERE pengajuan.calon_dosen = ?
            AND (review_pengajuan.status IS NULL OR review_pengajuan.status = 'Pending')
        ", [$nidn])->getRowArray();

        $acc = $this->db->query("
            SELECT COUNT(*) as total FROM pengajuan
            JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
            WHERE pengajuan.calon_dosen = ?
            AND review_pengajuan.status = 'ACC'
        ", [$nidn])->getRowArray();

        $tolak = $this->db->query("
            SELECT COUNT(*) as total FROM pengajuan
            JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
            WHERE pengajuan.calon_dosen = ?
            AND review_pengajuan.status = 'Ditolak'
        ", [$nidn])->getRowArray();

        $total_bimbingan_ditetapkan = $this->db->query("
            SELECT COUNT(*) as total FROM pengajuan
            JOIN pembimbing ON pengajuan.id_pengajuan = pembimbing.id_pengajuan
            WHERE pengajuan.status = 'Ditetapkan'
            AND pengajuan.kondisi_data = 'Aktif'
            AND (
            pembimbing.pembimbing_1 = ?
            OR pembimbing.pembimbing_2 = ?
            )
        ", [$nidn, $nidn])->getRowArray();

        return view('dosen/dashboard', [
            'title'   => 'Dashboard Dosen',
            'total'   => $total,
            'pending' => $pending['total'],
            'acc'     => $acc['total'],
            'tolak'   => $tolak['total'],
            'total_dibimbing' => $total_bimbingan_ditetapkan['total'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Review Pengajuan
    |--------------------------------------------------------------------------
    */
    public function review()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $nidn  = session()->get('username');
        $dosen = $this->dosenModel->getByNidn($nidn);

        $total_bimbingan = max(0, $dosen['total_bimbingan']);
        $sisa_kuota      = $dosen['kuota_bimbingan'] - $total_bimbingan;

        // Ambil langsung terpisah dari controller
        $data_pending        = $this->pengajuanModel->getPendingMurniByDosen($nidn);
        $data_direvisi       = $this->pengajuanModel->getMenungguRevisiByDosen($nidn);
        $data_sudah_direvisi = $this->pengajuanModel->getSudahDirevisByDosen($nidn);

        return view('dosen/review', [
            'title'               => 'Review Pengajuan',
            'dosen'               => $dosen,
            'total_bimbingan'     => $total_bimbingan,
            'sisa_kuota'          => $sisa_kuota,
            'data_pending'        => $data_pending,
            'data_direvisi'       => $data_direvisi,
            'data_sudah_direvisi' => $data_sudah_direvisi,
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | Proses Review
    |--------------------------------------------------------------------------
    */
    public function proses_review()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id       = $this->request->getPost('id');
        $status   = $this->request->getPost('status');
        $feedback = trim($this->request->getPost('feedback'));
        $nidn     = session()->get('username');
        $tgl      = date('Y-m-d');

        $cek = $this->pengajuanModel
            ->where('id_pengajuan', $id)
            ->where('calon_dosen', $nidn)
            ->first();

        if (!$cek) {
            return redirect()->to('/dosen/review');
        }

        // Jika ditolak, kurangi total bimbingan
        if ($status == 'Ditolak') {
            $this->dosenModel->kurangiBimbingan($nidn);
        }

        // Update status pengajuan
        $this->pengajuanModel->update($id, [
            'status' => $status
        ]);

        // Update review_pengajuan
        $review = $this->reviewModel->getByPengajuan($id);
        if ($review) {
            $this->reviewModel->update($review['id_review'], [
                'feedback'   => $feedback,
                'status'     => $status,
                'tgl_review' => $tgl
            ]);
        } else {
            $this->reviewModel->insert([
                'id_pengajuan' => $id,
                'nidn'         => $nidn,
                'feedback'     => $feedback,
                'status'       => $status,
                'tgl_review'   => $tgl
            ]);
        }

        return redirect()->to('/dosen/review');
    }

    /*
    |--------------------------------------------------------------------------
    | Riwayat Review
    |--------------------------------------------------------------------------
    */
    public function riwayat_review()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $nidn = session()->get('username');

        $data_acc   = $this->pengajuanModel->getByDosenDanStatus($nidn, 'ACC');
        $data_tolak = $this->pengajuanModel->getByDosenDanStatus($nidn, 'Ditolak');

        // Tambahkan pengambilan status Ditolak Permanen
        $data_batal = $this->pengajuanModel->getByDosenDanStatus($nidn, 'Ditolak Permanen');

        return view('dosen/riwayat_review', [
            'title'      => 'Riwayat Review',
            'data_acc'   => $data_acc,
            'data_tolak' => $data_tolak,
            'data_batal' => $data_batal, // Kirim ke view
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Mahasiswa Bimbingan
    |--------------------------------------------------------------------------
    */
    public function mahasiswa_bimbingan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $nidn = session()->get('username');

        $semua_bimbingan = $this->pengajuanModel->getMahasiswaBimbinganByDosen($nidn);

        // DITAMBAHKAN array_values() AGAR INDEX ARRAY TIDAK ACAK-ACAKAN
        $bimbingan_aktif = array_values(array_filter($semua_bimbingan, function ($row) {
            return $row['status_bimbingan'] == 'Aktif';
        }));

        $bimbingan_batal = array_values(array_filter($semua_bimbingan, function ($row) {
            return $row['status_bimbingan'] == 'Batal';
        }));

        return view('dosen/mahasiswa_bimbingan', [
            'title'            => 'Mahasiswa Bimbingan',
            'nidn'            => $nidn,
            'bimbingan_aktif' => $bimbingan_aktif,
            'bimbingan_batal' => $bimbingan_batal
        ]);
    }


    // ==========================================
    // FITUR LOGBOOK DOSEN
    // ==========================================
    public function logbook_bimbingan()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $nidn = session()->get('username');
        $logbookModel = new \App\Models\LogbookModel();

        $data_logbook = $logbookModel->getLogbookDosen($nidn);

        return view('dosen/logbook', [
            'title'   => 'Logbook',
            'logbook' => $data_logbook
        ]);
    }

    public function validasi_logbook()
    {
        $redirect = $this->cekRole();
        if ($redirect) return $redirect;

        $id_logbook = $this->request->getPost('id_logbook');
        $logbookModel = new \App\Models\LogbookModel();

        $dataUpdate = [
            'status_validasi' => $this->request->getPost('status_validasi'),
            'catatan_dosen'   => $this->request->getPost('catatan_dosen'),
            'file_review_url' => $this->request->getPost('file_review_url'),
            // Tambahkan baris ini untuk merekam waktu otomatis
            'waktu_review'    => date('Y-m-d H:i:s')
        ];

        $logbookModel->update($id_logbook, $dataUpdate);

        return redirect()->to('/dosen/logbook')->with('success', 'Validasi & Link Review berhasil disimpan!');
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
