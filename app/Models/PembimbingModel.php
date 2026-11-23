<?php

namespace App\Models;

use CodeIgniter\Model;

class PembimbingModel extends Model
{
    protected $table      = 'pembimbing';
    protected $primaryKey = 'id';
    protected $allowedFields = [
    'id_pengajuan',
    'pembimbing_1',
    'pembimbing_2',
    'file_pdf',
    'tgl_penetapan',
    'status_bimbingan'
    ];

    public function getByPengajuan($id_pengajuan)
    {
        return $this->where('id_pengajuan', $id_pengajuan)->first();
    }
}