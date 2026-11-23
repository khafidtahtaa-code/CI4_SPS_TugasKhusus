<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table      = 'review_pengajuan';
    protected $primaryKey = 'id_review';
    protected $allowedFields = [
        'id_pengajuan', 'nidn', 'feedback', 'status', 'tgl_review', 'sudah_direvisi', 'feedback_lama'
    ];

    public function getByPengajuan($id_pengajuan)
    {
        return $this->where('id_pengajuan', $id_pengajuan)->first();
    }
}