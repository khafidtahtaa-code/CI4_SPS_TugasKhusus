<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table      = 'mahasiswa';
    protected $primaryKey = 'npm';
    protected $allowedFields = ['npm', 'nama', 'angkatan', 'user_id'];

    public function getAll()
    {
        return $this->findAll();
    }

    public function getByNpm($npm)
    {
        return $this->find($npm);
    }
}