<?php

namespace App\Models;

use CodeIgniter\Model;

class ArsipModel extends Model
{
    protected $table      = 'arsip_skripsi';
    protected $primaryKey = 'id_arsip';
    protected $allowedFields = [
    'npm', 'nama_mahasiswa', 'angkatan', 'judul', 'abstrak',
    'kata_kunci', 'id_topik', 'nama_topik_arsip', 'kategori_bidang',
    'pembimbing_1', 'pembimbing_2',
    'nama_pembimbing1_arsip', 'nama_pembimbing2_arsip',
    'tahun_lulus', 'file_url', 'tgl_arsip'
    ];

    // Ambil semua arsip dengan JOIN
public function getArsipLengkap()
{
    return $this->db->query("
        SELECT
            arsip_skripsi.*,
            COALESCE(topik_skripsi.nama_topik, arsip_skripsi.nama_topik_arsip) AS nama_topik,
            COALESCE(d1.nama, arsip_skripsi.nama_pembimbing1_arsip) AS nama_pembimbing1,
            COALESCE(d2.nama, arsip_skripsi.nama_pembimbing2_arsip) AS nama_pembimbing2
        FROM arsip_skripsi
        LEFT JOIN topik_skripsi ON arsip_skripsi.id_topik = topik_skripsi.id_topik
        LEFT JOIN dosen d1 ON arsip_skripsi.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON arsip_skripsi.pembimbing_2 = d2.nidn
        ORDER BY arsip_skripsi.tahun_lulus DESC
    ")->getResultArray();
}

public function searchArsip($keyword = '', $tahun = '', $topik = '')
{
    $sql = "
        SELECT
            arsip_skripsi.*,
            COALESCE(topik_skripsi.nama_topik, arsip_skripsi.nama_topik_arsip) AS nama_topik,
            COALESCE(d1.nama, arsip_skripsi.nama_pembimbing1_arsip) AS nama_pembimbing1,
            COALESCE(d2.nama, arsip_skripsi.nama_pembimbing2_arsip) AS nama_pembimbing2
        FROM arsip_skripsi
        LEFT JOIN topik_skripsi ON arsip_skripsi.id_topik = topik_skripsi.id_topik
        LEFT JOIN dosen d1 ON arsip_skripsi.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON arsip_skripsi.pembimbing_2 = d2.nidn
        WHERE 1=1
    ";

    $params = [];

    if (!empty($keyword)) {
        $sql .= " AND (
            arsip_skripsi.judul LIKE ?
            OR arsip_skripsi.abstrak LIKE ?
            OR arsip_skripsi.kata_kunci LIKE ?
            OR arsip_skripsi.nama_mahasiswa LIKE ?
        )";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
    }

    if (!empty($tahun)) {
        $sql .= " AND arsip_skripsi.tahun_lulus = ?";
        $params[] = $tahun;
    }

    if (!empty($topik)) {
        $sql .= " AND arsip_skripsi.id_topik = ?";
        $params[] = $topik;
    }

    $sql .= " ORDER BY arsip_skripsi.tahun_lulus DESC";

    return $this->db->query($sql, $params)->getResultArray();
}

public function autoArchive($pengajuan, $pembimbing, $mahasiswa)
{
    // Ambil nama topik dan dosen dari sistem
    $topik = \Config\Database::connect()->query("
        SELECT nama_topik FROM topik_skripsi WHERE id_topik = ?
    ", [$pengajuan['id_topik']])->getRowArray();

    $dsn1 = \Config\Database::connect()->query("
        SELECT nama FROM dosen WHERE nidn = ?
    ", [$pembimbing['pembimbing_1']])->getRowArray();

    $dsn2 = \Config\Database::connect()->query("
        SELECT nama FROM dosen WHERE nidn = ?
    ", [$pembimbing['pembimbing_2']])->getRowArray();

    return $this->insert([
        'npm'                    => $pengajuan['npm'],
        'nama_mahasiswa'         => $mahasiswa['nama'],
        'angkatan'               => $mahasiswa['angkatan'],
        'judul'                  => $pengajuan['judul'],
        'abstrak'                => $pengajuan['abstrak'],
        'kata_kunci'             => '',
        'id_topik'               => $pengajuan['id_topik'],
        'nama_topik_arsip'       => $topik['nama_topik'] ?? '',
        'kategori_bidang'        => '',
        'pembimbing_1'           => $pembimbing['pembimbing_1'],
        'pembimbing_2'           => $pembimbing['pembimbing_2'],
        'nama_pembimbing1_arsip' => $dsn1['nama'] ?? '',
        'nama_pembimbing2_arsip' => $dsn2['nama'] ?? '',
        'tahun_lulus'            => date('Y'),
        'file_url'               => $pembimbing['file_pdf'],
        'tgl_arsip'              => date('Y-m-d H:i:s')
    ]);
}

    // Statistik topik per tahun
    public function getStatistikTopik($tahun = '')
    {
        $sql = "
            SELECT
                topik_skripsi.nama_topik,
                COUNT(*) as total
            FROM arsip_skripsi
            LEFT JOIN topik_skripsi ON arsip_skripsi.id_topik = topik_skripsi.id_topik
        ";

        $params = [];
        if (!empty($tahun)) {
            $sql .= " WHERE arsip_skripsi.tahun_lulus = ?";
            $params[] = $tahun;
        }

        $sql .= " GROUP BY arsip_skripsi.id_topik ORDER BY total DESC";

        return $this->db->query($sql, $params)->getResultArray();
    }

public function cekDuplikasi($judul, $id_pengajuan = null): array
{
    $keyword = '%' . $judul . '%';
    
    // Cek di arsip_skripsi
    $arsip = $this->db->query("
        SELECT judul, nama_mahasiswa, tahun_lulus, 'Arsip' AS sumber
        FROM arsip_skripsi
        WHERE judul LIKE ?
        LIMIT 5
    ", [$keyword])->getResultArray();

    // Cek di tabel pengajuan yang sedang berjalan
    // Exclude pengajuan milik sendiri yang sedang diedit
    $sql = "
        SELECT pengajuan.judul, mahasiswa.nama AS nama_mahasiswa, 
               pengajuan.tgl_pengajuan AS tahun_lulus, 'Pengajuan Aktif' AS sumber
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        WHERE pengajuan.judul LIKE ?
        AND pengajuan.kondisi_data = 'Aktif'
        AND pengajuan.status != 'Dibatalkan'
    ";

    $params = [$keyword];

    // Kalau ada id_pengajuan, exclude pengajuan itu sendiri
    if ($id_pengajuan) {
        $sql .= " AND pengajuan.id_pengajuan != ?";
        $params[] = $id_pengajuan;
    }

    $sql .= " LIMIT 5";

    $pengajuan = $this->db->query($sql, $params)->getResultArray();

    return array_merge($arsip, $pengajuan);
    
    }
}