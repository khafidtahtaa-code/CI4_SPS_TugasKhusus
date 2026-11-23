<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table      = 'dosen';
    protected $primaryKey = 'nidn';
    protected $allowedFields = [
    'nidn',
    'nama',
    'bidang_keahlian',
    'kuota_bimbingan',
    'total_bimbingan',
    'total_p1',
    'total_p2',
    'maks_p1',
    'maks_p2',
    'user_id'
    ];

    public function getAll()
    {
        return $this->findAll();
    }

    public function getByNidn($nidn)
    {
        return $this->find($nidn);
    }

    public function getDosenTersedia()
    {
    return $this->db->query("
        SELECT * FROM dosen 
        WHERE total_bimbingan < kuota_bimbingan
    ")->getResultArray();
    }

    public function tambahBimbingan($nidn)
    {
        $this->db->query("UPDATE dosen SET total_bimbingan = total_bimbingan + 1 WHERE nidn = ?", [$nidn]);
    }

public function kurangiBimbingan($nidn)
{
    // Pastikan pakai GREATEST, jangan MAX biar MariaDB-nya ga marah lagi
    $this->db->query("UPDATE dosen SET total_bimbingan = GREATEST(0, total_bimbingan - 1) WHERE nidn = ?", [$nidn]);
}
}