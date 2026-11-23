<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model {
    protected $table = 'notifikasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'pesan', 'link', 'is_read', 'created_at'];

    // Fungsi untuk menambah notifikasi
    public function kirim($user_id, $pesan, $link) {
        $this->insert([
            'user_id' => $user_id,
            'pesan' => $pesan,
            'link' => $link
        ]);
    }

    // Fungsi ambil notifikasi unread
    public function getUnread($user_id) {
        return $this->where('user_id', $user_id)->where('is_read', 0)->findAll();
    }
}