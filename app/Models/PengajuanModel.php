<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table      = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';
    protected $allowedFields = [
        'npm',
        'judul',
        'abstrak',
        'id_topik',
        'calon_dosen',
        'status',
        'kondisi_data',
        'tgl_pengajuan',
        'feedback_admin',
        'tgl_dibatalkan_ditolak'
    ];

    public function getPengajuanLengkap()
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.user_id,
            mahasiswa.angkatan,
            topik_skripsi.nama_topik,
            dosen.nama AS nama_dosen,
            review_pengajuan.feedback,
            review_pengajuan.status AS status_review,
            review_pengajuan.tgl_review,
            pembimbing.pembimbing_1,
            pembimbing.pembimbing_2,
            pembimbing.file_pdf,
            pembimbing.tgl_penetapan,
            d1.nama AS nama_pembimbing1,
            d2.nama AS nama_pembimbing2
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        JOIN dosen ON pengajuan.calon_dosen = dosen.nidn
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        LEFT JOIN pembimbing ON pengajuan.id_pengajuan = pembimbing.id_pengajuan
        LEFT JOIN dosen d1 ON pembimbing.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON pembimbing.pembimbing_2 = d2.nidn
        ORDER BY pengajuan.id_pengajuan DESC -- <--- PERBAIKAN: Diurutkan dari yang terbaru
    ")->getResultArray();
    }

    public function getByNpm($npm)
    {
        // Menggunakan LEFT JOIN di semua tabel agar jika ada data yang kosong, 
        // pengajuannya tidak hilang dan tidak jadi NULL di halaman Mahasiswa
        return $this->db->query("
        SELECT
            pengajuan.*,
            topik_skripsi.nama_topik,
            dosen.nama AS nama_dosen,
            review_pengajuan.feedback,
            review_pengajuan.status AS status_review,
            review_pengajuan.tgl_review,
            pembimbing.pembimbing_1,
            pembimbing.pembimbing_2,
            pembimbing.file_pdf,
            pembimbing.tgl_penetapan,
            pembimbing.status_bimbingan,
            d1.nama AS nama_pembimbing1,
            d2.nama AS nama_pembimbing2
        FROM pengajuan
        LEFT JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        LEFT JOIN dosen ON pengajuan.calon_dosen = dosen.nidn
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        LEFT JOIN pembimbing ON pengajuan.id_pengajuan = pembimbing.id_pengajuan
        LEFT JOIN dosen d1 ON pembimbing.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON pembimbing.pembimbing_2 = d2.nidn
        WHERE pengajuan.npm = ?
        ORDER BY pengajuan.id_pengajuan DESC
        LIMIT 1
    ", [$npm])->getRowArray();
    }

    public function getPengajuanAktifByNpm($npm)
    {
        return $this->db->query("
        SELECT pengajuan.*, review_pengajuan.status AS status_review
        FROM pengajuan
        LEFT JOIN review_pengajuan
            ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        WHERE pengajuan.npm = ?
        AND pengajuan.kondisi_data = 'Aktif'
        ORDER BY pengajuan.id_pengajuan DESC
        LIMIT 1
    ", [$npm])->getRowArray();
    }

    public function getPendingByDosen($nidn)
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.angkatan,
            topik_skripsi.nama_topik
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        WHERE pengajuan.calon_dosen = ?
        AND pengajuan.kondisi_data = 'Aktif'
        AND (
            review_pengajuan.status IS NULL
            OR review_pengajuan.status = 'Pending'
            OR review_pengajuan.status = 'Direvisi'
        )
    ", [$nidn])->getResultArray();
    }

    // Pending murni (belum ada review sama sekali atau status Pending)
    public function getPendingMurniByDosen($nidn)
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.angkatan,
            topik_skripsi.nama_topik,
            review_pengajuan.status AS status_review,
            review_pengajuan.feedback,
            review_pengajuan.tgl_review
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        WHERE pengajuan.calon_dosen = ?
        AND pengajuan.kondisi_data = 'Aktif'
        AND (
            review_pengajuan.status IS NULL
            OR (
                review_pengajuan.status = 'Pending'
                AND (review_pengajuan.sudah_direvisi = 0 OR review_pengajuan.sudah_direvisi IS NULL)
            )
        )
    ", [$nidn])->getResultArray();
    }

    // Menunggu revisi mahasiswa (dosen sudah klik Minta Revisi)
    public function getMenungguRevisiByDosen($nidn)
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.angkatan,
            topik_skripsi.nama_topik,
            review_pengajuan.status AS status_review,
            review_pengajuan.feedback,
            review_pengajuan.tgl_review
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        WHERE pengajuan.calon_dosen = ?
        AND pengajuan.kondisi_data = 'Aktif'
        AND review_pengajuan.status = 'Direvisi'
    ", [$nidn])->getResultArray();
    }

    public function getSudahDirevisByDosen($nidn)
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.angkatan,
            topik_skripsi.nama_topik,
            review_pengajuan.feedback,
            review_pengajuan.feedback_lama,
            review_pengajuan.status AS status_review,
            review_pengajuan.tgl_review,
            review_pengajuan.sudah_direvisi
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        WHERE pengajuan.calon_dosen = ?
        AND pengajuan.kondisi_data = 'Aktif'
        AND review_pengajuan.status = 'Pending'
        AND review_pengajuan.sudah_direvisi = 1
    ", [$nidn])->getResultArray();
    }

    public function getByDosenDanStatus($nidn, $status): array
    {
        // Kita gunakan logika OR untuk menangani status khusus
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.angkatan,
            topik_skripsi.nama_topik,
            review_pengajuan.feedback,
            review_pengajuan.status AS status_review,
            review_pengajuan.tgl_review
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        WHERE pengajuan.calon_dosen = ?
        AND (review_pengajuan.status = ? OR pengajuan.status = ?)
    ", [$nidn, $status, $status])->getResultArray();
        // Kita kirim $status dua kali untuk mengisi placeholder ? yang pertama dan kedua
    }

    public function getMahasiswaBimbinganByDosen($nidn)
    {
        // Mengembalikan semua kolom asli yang dibutuhkan View Dosen
        return $this->db->query("
        SELECT pengajuan.*, 
               mahasiswa.nama AS nama_mahasiswa, 
               mahasiswa.angkatan, 
               topik_skripsi.nama_topik, 
               pembimbing.pembimbing_1, 
               pembimbing.pembimbing_2, 
               pembimbing.tgl_penetapan, 
               pembimbing.status_bimbingan, 
               d1.nama AS nama_pembimbing1, 
               d2.nama AS nama_pembimbing2,
               review_pengajuan.status AS status_review
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        LEFT JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        JOIN pembimbing ON pengajuan.id_pengajuan = pembimbing.id_pengajuan
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        LEFT JOIN dosen d1 ON pembimbing.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON pembimbing.pembimbing_2 = d2.nidn
        WHERE (pembimbing.pembimbing_1 = ? OR pembimbing.pembimbing_2 = ?)
        AND pembimbing.status_bimbingan IN ('Aktif', 'Batal')
        ORDER BY pembimbing.status_bimbingan ASC, pembimbing.tgl_penetapan DESC
    ", [$nidn, $nidn])->getResultArray();
    }

    public function getSiapDitetapkan()
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            topik_skripsi.nama_topik,
            dosen.nama AS nama_dosen,
            review_pengajuan.feedback,
            review_pengajuan.status AS status_review
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        JOIN dosen ON pengajuan.calon_dosen = dosen.nidn
        JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        WHERE pengajuan.status != 'Ditetapkan'
        AND pengajuan.kondisi_data = 'Aktif'
        AND (
            review_pengajuan.status = 'ACC'
            OR review_pengajuan.status = 'Ditolak'
        )
    ")->getResultArray();
    }

    public function getSudahDitetapkan()
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            mahasiswa.angkatan,
            topik_skripsi.nama_topik,
            pembimbing.pembimbing_1,
            pembimbing.pembimbing_2,
            pembimbing.file_pdf,
            pembimbing.tgl_penetapan,
            pembimbing.status_bimbingan,
            d1.nama AS nama_pembimbing1,
            d2.nama AS nama_pembimbing2

        FROM pengajuan

        JOIN mahasiswa
            ON pengajuan.npm = mahasiswa.npm

        JOIN topik_skripsi
            ON pengajuan.id_topik = topik_skripsi.id_topik

        LEFT JOIN pembimbing
            ON pengajuan.id_pengajuan = pembimbing.id_pengajuan

        LEFT JOIN dosen d1
            ON pembimbing.pembimbing_1 = d1.nidn

        LEFT JOIN dosen d2
            ON pembimbing.pembimbing_2 = d2.nidn

        WHERE pengajuan.status = 'Ditetapkan'
        AND pengajuan.kondisi_data = 'Aktif'
        AND pembimbing.status_bimbingan = 'Aktif'

        ORDER BY pembimbing.tgl_penetapan DESC
    ")->getResultArray();
    }

    public function getArsip()
    {
        return $this->db->query("
            SELECT
                pengajuan.*,
                mahasiswa.nama AS nama_mahasiswa,
                topik_skripsi.nama_topik,
                pembimbing.pembimbing_1,
                pembimbing.pembimbing_2,
                d1.nama AS nama_pembimbing1,
                d2.nama AS nama_pembimbing2
            FROM pengajuan
            JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
            JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
            LEFT JOIN pembimbing ON pengajuan.id_pengajuan = pembimbing.id_pengajuan
            LEFT JOIN dosen d1 ON pembimbing.pembimbing_1 = d1.nidn
            LEFT JOIN dosen d2 ON pembimbing.pembimbing_2 = d2.nidn
            WHERE pengajuan.kondisi_data = 'Diarsipkan'
        ")->getResultArray();
    }

    public function getDibatalkan()
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            mahasiswa.nama AS nama_mahasiswa,
            topik_skripsi.nama_topik,
            pembimbing.pembimbing_1,
            pembimbing.pembimbing_2,
            pembimbing.status_bimbingan,
            d1.nama AS nama_pembimbing1,
            d2.nama AS nama_pembimbing2
        FROM pengajuan
        JOIN mahasiswa ON pengajuan.npm = mahasiswa.npm
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        LEFT JOIN pembimbing ON pengajuan.id_pengajuan = pembimbing.id_pengajuan
        LEFT JOIN dosen d1 ON pembimbing.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON pembimbing.pembimbing_2 = d2.nidn
        WHERE pengajuan.kondisi_data = 'Dibatalkan'
    ")->getResultArray();
    }

    public function getRiwayatByNpm($npm)
    {
        return $this->db->query("
        SELECT
            pengajuan.*,
            topik_skripsi.nama_topik,
            dosen.nama AS nama_dosen,
            review_pengajuan.feedback,
            review_pengajuan.status AS status_review,
            review_pengajuan.tgl_review,
            pembimbing.pembimbing_1,
            pembimbing.pembimbing_2,
            pembimbing.file_pdf,
            pembimbing.tgl_penetapan,
            d1.nama AS nama_pembimbing1,
            d2.nama AS nama_pembimbing2
        FROM pengajuan
        JOIN topik_skripsi ON pengajuan.id_topik = topik_skripsi.id_topik
        JOIN dosen ON pengajuan.calon_dosen = dosen.nidn
        LEFT JOIN review_pengajuan ON pengajuan.id_pengajuan = review_pengajuan.id_pengajuan
        LEFT JOIN pembimbing ON pengajuan.id_pengajuan = pembimbing.id_pengajuan
        LEFT JOIN dosen d1 ON pembimbing.pembimbing_1 = d1.nidn
        LEFT JOIN dosen d2 ON pembimbing.pembimbing_2 = d2.nidn
        WHERE pengajuan.npm = ?
        -- PERBAIKAN: Diubah jadi ASC agar data lama di atas (Pengajuan 1) dan data baru di bawah (Pengajuan 2)
        ORDER BY pengajuan.id_pengajuan ASC 
    ", [$npm])->getResultArray();
    }

    public function getStatistikTopik()
    {
        return $this->db->query("
        SELECT
            topik_skripsi.nama_topik,
            COUNT(pengajuan.id_pengajuan) as total
        FROM topik_skripsi
        LEFT JOIN pengajuan ON pengajuan.id_topik = topik_skripsi.id_topik
        GROUP BY topik_skripsi.id_topik
        ORDER BY total DESC
    ")->getResultArray();
    }

    public function getStatistikStatus()
    {
        return $this->db->query("
        SELECT
            pengajuan.status,
            COUNT(*) as total
        FROM pengajuan
        GROUP BY pengajuan.status
    ")->getResultArray();
    }

    public function getStatistikPerTahun()
    {
        return $this->db->query("
        SELECT
            YEAR(tgl_pengajuan) as tahun,
            COUNT(*) as total
        FROM pengajuan
        GROUP BY YEAR(tgl_pengajuan)
        ORDER BY tahun ASC
    ")->getResultArray();
    }
}
