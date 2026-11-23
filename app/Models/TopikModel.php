<?php

namespace App\Models;

use CodeIgniter\Model;

class TopikModel extends Model
{
    protected $table      = 'topik_skripsi';
    protected $primaryKey = 'id_topik';
    protected $allowedFields = ['id_topik', 'nama_topik'];

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($id)
    {
        return $this->find($id);
    }
}