<?php

namespace App\Models;

use CodeIgniter\Model;

class LogbookModel extends Model
{
    protected $table            = 'logbook_bimbingan';
    protected $primaryKey       = 'id_logbook';
    protected $allowedFields    = [
        'id_pengajuan', 'npm', 'nidn_dosen', 'tanggal_bimbingan', 
        'materi_bimbingan', 'catatan_dosen', 'file_review_url', 'file_mahasiswa_url', 'waktu_review', 'status_validasi', 'created_at'
    ];

    // Ambil logbook milik mahasiswa tertentu (beserta nama dosennya)
public function getLogbookMahasiswa($npm)
{
    return $this->db->table($this->table)
        ->select('logbook_bimbingan.*, dosen.nama as nama_dosen, pengajuan.judul, pengajuan.status as status_pengajuan')
        ->join('dosen', 'dosen.nidn = logbook_bimbingan.nidn_dosen', 'left')
        ->join('pengajuan', 'pengajuan.id_pengajuan = logbook_bimbingan.id_pengajuan', 'left')
        ->where('logbook_bimbingan.npm', $npm)
        // 1. URUTKAN BERDASARKAN PENGAJUAN TERBARU (Agar mengelompok)
        ->orderBy('logbook_bimbingan.id_pengajuan', 'DESC') 
        // 2. URUTKAN TANGGAL BIMBINGAN TERBARU DI DALAM KELOMPOK TERSEBUT
        ->orderBy('logbook_bimbingan.tanggal_bimbingan', 'DESC')
        ->get()->getResultArray();
}

    // Ambil daftar logbook yang perlu divalidasi oleh dosen tertentu
    public function getLogbookDosen($nidn)
    {
        return $this->db->table($this->table)
            ->select('logbook_bimbingan.*, mahasiswa.nama as nama_mahasiswa, pengajuan.judul')
            ->join('mahasiswa', 'mahasiswa.npm = logbook_bimbingan.npm', 'left')
            ->join('pengajuan', 'pengajuan.id_pengajuan = logbook_bimbingan.id_pengajuan', 'left')
            ->where('logbook_bimbingan.nidn_dosen', $nidn)
            // 1. TAMBAHKAN INI: Urutkan nama mahasiswa agar fitur "Grouping/Garis Pembatas" di View berfungsi
            ->orderBy('mahasiswa.nama', 'ASC') 
            // 2. Pengurutan bawaanmu: Status "Menunggu" diprioritaskan di atas
            ->orderBy('logbook_bimbingan.status_validasi', 'ASC') 
            // 3. Pengurutan bawaanmu: Tanggal bimbingan terbaru
            ->orderBy('logbook_bimbingan.tanggal_bimbingan', 'DESC')
            ->get()->getResultArray();
    }
}

