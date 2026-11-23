<?php

namespace App\Controllers;

use App\Models\ArsipModel;
use App\Models\TopikModel;

class Publik extends BaseController
{
    protected $arsipModel;
    protected $topikModel;

    public function __construct()
    {
        $this->arsipModel = new ArsipModel();
        $this->topikModel = new TopikModel();
    }


    // Di dalam class Publik
public function arsip()
{
    // 1. Ambil input pencarian
    $keyword = $this->request->getGet('keyword') ?? '';
    $tahun   = $this->request->getGet('tahun') ?? '';
    $topik   = $this->request->getGet('topik') ?? '';

    $db = \Config\Database::connect();

    // ==========================================
    // 2. DATA UNTUK BARIS 1: KHUSUS ALUMNI (IMPORT EXCEL)
    // ==========================================
    $builderAlumni = $db->table('arsip_skripsi')->where('kategori_arsip', 'alumni');
    if (!empty($keyword)) {
        $builderAlumni->groupStart()
                      ->like('judul', $keyword)
                      ->orLike('nama_mahasiswa', $keyword)
                      ->orLike('npm', $keyword)
                      ->groupEnd();
    }
    if (!empty($tahun)) $builderAlumni->where('tahun_lulus', $tahun);
    if (!empty($topik)) $builderAlumni->where('id_topik', $topik); // sesuaikan nama kolom topik jika beda
    $alumni = $builderAlumni->get()->getResultArray();

    // ==========================================
    // 3. DATA UNTUK BARIS 2: KHUSUS MAHASISWA BARU LULUS (SISTEM)
    // ==========================================
    $builderLulus = $db->table('arsip_skripsi')->where('kategori_arsip', 'lulus_tahun_ini');
    if (!empty($keyword)) {
        $builderLulus->groupStart()
                     ->like('judul', $keyword)
                     ->orLike('nama_mahasiswa', $keyword)
                     ->orLike('npm', $keyword)
                     ->groupEnd();
    }
    if (!empty($tahun)) $builderLulus->where('tahun_lulus', $tahun);
    if (!empty($topik)) $builderLulus->where('id_topik', $topik); // sesuaikan nama kolom topik jika beda
    $lulus_tahun_ini = $builderLulus->get()->getResultArray();

    // 4. Data pendukung filter (Bawaan kode asli kamu)
    $topik_list = $this->topikModel->getAll();
    $tahun_list = $db->query("
        SELECT DISTINCT tahun_lulus FROM arsip_skripsi 
        ORDER BY tahun_lulus DESC
    ")->getResultArray();

    // 5. Kirim semua ke view
    return view('publik/arsip', [
        'alumni'          => $alumni,          // Dikirim untuk isi Baris 1
        'lulus_tahun_ini' => $lulus_tahun_ini, // Dikirim untuk isi Baris 2
        'topik_list'      => $topik_list,
        'tahun_list'      => $tahun_list,
        'keyword'         => $keyword,
        'tahun'           => $tahun,
        'topik'           => $topik,
    ]);
}

public function detail($id)
{
    $db   = \Config\Database::connect();
    $data = $db->query("
        SELECT
            arsip_skripsi.*,
            COALESCE(topik_skripsi.nama_topik, arsip_skripsi.nama_topik_arsip) AS nama_topik,
            COALESCE(d1.nama, arsip_skripsi.nama_pembimbing1_arsip) AS nama_pembimbing1,
            COALESCE(d2.nama, arsip_skripsi.nama_pembimbing2_arsip) AS nama_pembimbing2
        FROM arsip_skripsi
        LEFT JOIN topik_skripsi ON arsip_skripsi.id_topik = topik_skripsi.id_topik
        LEFT JOIN dosen d1 ON arsip_skripsi.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON arsip_skripsi.pembimbing_2 = d2.nidn
        WHERE arsip_skripsi.id_arsip = ?
    ", [$id])->getRowArray();

    if (!$data) {
        return redirect()->to('/publik/arsip');
    }

    return view('publik/detail_arsip', ['data' => $data]);
}
}