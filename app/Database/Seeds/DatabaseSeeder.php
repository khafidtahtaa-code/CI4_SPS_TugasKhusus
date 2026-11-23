<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. DROP SEMUA TABEL (RESET)
        // ==========================================
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0;");
        $this->db->query("DROP TABLE IF EXISTS logbook_bimbingan;");
        $this->db->query("DROP TABLE IF EXISTS arsip_skripsi;");
        $this->db->query("DROP TABLE IF EXISTS pembimbing;");
        $this->db->query("DROP TABLE IF EXISTS review_pengajuan;");
        $this->db->query("DROP TABLE IF EXISTS pengajuan;");
        $this->db->query("DROP TABLE IF EXISTS mahasiswa;");
        $this->db->query("DROP TABLE IF EXISTS dosen;");
        $this->db->query("DROP TABLE IF EXISTS notifikasi;");
        $this->db->query("DROP TABLE IF EXISTS topik_skripsi;");
        $this->db->query("DROP TABLE IF EXISTS users;");
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1;");

        // ==========================================
        // 2. CREATE TABEL
        // ==========================================
        
        // TABEL USERS
        $this->db->query("
            CREATE TABLE users (
                id int(11) NOT NULL AUTO_INCREMENT, 
                username varchar(100) DEFAULT NULL, 
                password varchar(255) DEFAULT NULL, 
                role varchar(20) DEFAULT NULL, 
                PRIMARY KEY (id), 
                UNIQUE KEY username (username)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL TOPIK
        $this->db->query("
            CREATE TABLE topik_skripsi (
                id_topik int(11) NOT NULL, 
                nama_topik varchar(100) DEFAULT NULL, 
                PRIMARY KEY (id_topik), 
                UNIQUE KEY nama_topik (nama_topik)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL DOSEN
        $this->db->query("
            CREATE TABLE dosen (
                nidn varchar(20) NOT NULL, 
                nama varchar(100) DEFAULT NULL, 
                bidang_keahlian varchar(100) DEFAULT NULL, 
                kuota_bimbingan int(11) DEFAULT 5, 
                total_bimbingan int(11) DEFAULT 0, 
                user_id int(11) DEFAULT NULL, 
                total_p1 int(11) DEFAULT 0, 
                total_p2 int(11) DEFAULT 0, 
                maks_p1 int(11) DEFAULT 5, 
                maks_p2 int(11) DEFAULT 5, 
                PRIMARY KEY (nidn), 
                KEY user_id (user_id), 
                CONSTRAINT dosen_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL MAHASISWA
        $this->db->query("
            CREATE TABLE mahasiswa (
                npm varchar(20) NOT NULL, 
                nama varchar(100) DEFAULT NULL, 
                angkatan varchar(10) DEFAULT NULL, 
                user_id int(11) DEFAULT NULL, 
                PRIMARY KEY (npm), 
                KEY user_id (user_id), 
                CONSTRAINT mahasiswa_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL PENGAJUAN
        $this->db->query("
            CREATE TABLE pengajuan (
                id_pengajuan int(11) NOT NULL AUTO_INCREMENT, 
                npm varchar(20) DEFAULT NULL, 
                judul text DEFAULT NULL, 
                abstrak text DEFAULT NULL, 
                id_topik int(11) DEFAULT NULL, 
                calon_dosen varchar(20) DEFAULT NULL, 
                status varchar(20) DEFAULT 'Pending', 
                kondisi_data varchar(20) DEFAULT 'Aktif', 
                tgl_pengajuan datetime DEFAULT NULL, 
                feedback_admin varchar(225) DEFAULT NULL, 
                tgl_dibatalkan_ditolak datetime DEFAULT NULL, 
                PRIMARY KEY (id_pengajuan), 
                KEY npm (npm), 
                KEY id_topik (id_topik), 
                KEY calon_dosen (calon_dosen), 
                CONSTRAINT pengajuan_ibfk_1 FOREIGN KEY (npm) REFERENCES mahasiswa (npm) ON DELETE CASCADE, 
                CONSTRAINT pengajuan_ibfk_2 FOREIGN KEY (id_topik) REFERENCES topik_skripsi (id_topik) ON DELETE CASCADE, 
                CONSTRAINT pengajuan_ibfk_3 FOREIGN KEY (calon_dosen) REFERENCES dosen (nidn) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL PEMBIMBING
        $this->db->query("
            CREATE TABLE pembimbing (
                id int(11) NOT NULL AUTO_INCREMENT, 
                id_pengajuan int(11) DEFAULT NULL, 
                pembimbing_1 varchar(20) DEFAULT NULL, 
                pembimbing_2 varchar(20) DEFAULT NULL, 
                file_pdf varchar(255) DEFAULT NULL, 
                tgl_penetapan datetime DEFAULT NULL, 
                status_bimbingan varchar(20) DEFAULT 'Aktif', 
                PRIMARY KEY (id), 
                KEY id_pengajuan (id_pengajuan), 
                KEY pembimbing_1 (pembimbing_1), 
                KEY pembimbing_2 (pembimbing_2), 
                CONSTRAINT pembimbing_ibfk_1 FOREIGN KEY (id_pengajuan) REFERENCES pengajuan (id_pengajuan) ON DELETE CASCADE, 
                CONSTRAINT pembimbing_ibfk_2 FOREIGN KEY (pembimbing_1) REFERENCES dosen (nidn) ON DELETE CASCADE, 
                CONSTRAINT pembimbing_ibfk_3 FOREIGN KEY (pembimbing_2) REFERENCES dosen (nidn) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL REVIEW PENGAJUAN
        $this->db->query("
            CREATE TABLE review_pengajuan (
                id_review int(11) NOT NULL AUTO_INCREMENT, 
                id_pengajuan int(11) DEFAULT NULL, 
                nidn varchar(20) DEFAULT NULL, 
                feedback text DEFAULT NULL, 
                status varchar(20) DEFAULT NULL, 
                tgl_review date DEFAULT NULL, 
                sudah_direvisi tinyint(4) DEFAULT 0, 
                feedback_lama text DEFAULT NULL, 
                PRIMARY KEY (id_review), 
                KEY id_pengajuan (id_pengajuan), 
                KEY nidn (nidn), 
                CONSTRAINT review_pengajuan_ibfk_1 FOREIGN KEY (id_pengajuan) REFERENCES pengajuan (id_pengajuan) ON DELETE CASCADE, 
                CONSTRAINT review_pengajuan_ibfk_2 FOREIGN KEY (nidn) REFERENCES dosen (nidn) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL ARSIP
        $this->db->query("
            CREATE TABLE arsip_skripsi (
                id_arsip int(11) NOT NULL AUTO_INCREMENT, 
                npm varchar(20) DEFAULT NULL, 
                nama_mahasiswa varchar(100) DEFAULT NULL, 
                angkatan varchar(10) DEFAULT NULL, 
                judul text DEFAULT NULL, 
                abstrak text DEFAULT NULL, 
                kata_kunci varchar(255) DEFAULT NULL, 
                id_topik int(11) DEFAULT NULL, 
                nama_topik_arsip varchar(100) DEFAULT NULL, 
                pembimbing_1 varchar(20) DEFAULT NULL, 
                pembimbing_2 varchar(20) DEFAULT NULL, 
                nama_pembimbing1_arsip varchar(100) DEFAULT NULL, 
                nama_pembimbing2_arsip varchar(100) DEFAULT NULL, 
                tahun_lulus year(4) DEFAULT NULL, 
                file_url varchar(255) DEFAULT NULL, 
                tgl_arsip datetime DEFAULT NULL, 
                kategori_arsip enum('alumni','lulus_tahun_ini') DEFAULT 'alumni', 
                nilai_skripsi varchar(5) NOT NULL, 
                file_skripsi varchar(255) NOT NULL, 
                PRIMARY KEY (id_arsip), 
                KEY id_topik (id_topik), 
                KEY pembimbing_1 (pembimbing_1), 
                KEY pembimbing_2 (pembimbing_2), 
                CONSTRAINT arsip_skripsi_ibfk_1 FOREIGN KEY (id_topik) REFERENCES topik_skripsi (id_topik) ON DELETE SET NULL, 
                CONSTRAINT arsip_skripsi_ibfk_2 FOREIGN KEY (pembimbing_1) REFERENCES dosen (nidn) ON DELETE SET NULL, 
                CONSTRAINT arsip_skripsi_ibfk_3 FOREIGN KEY (pembimbing_2) REFERENCES dosen (nidn) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // TABEL LOGBOOK
        $this->db->query("
            CREATE TABLE logbook_bimbingan (
                id_logbook int(11) NOT NULL AUTO_INCREMENT, 
                id_pengajuan int(11) NOT NULL, 
                npm varchar(50) NOT NULL, 
                nidn_dosen varchar(50) NOT NULL, 
                tanggal_bimbingan date NOT NULL, 
                materi_bimbingan text NOT NULL, 
                catatan_dosen text DEFAULT NULL, 
                status_validasi enum('Menunggu','Revisi','Disetujui') NOT NULL DEFAULT 'Menunggu', 
                created_at datetime DEFAULT CURRENT_TIMESTAMP, 
                file_review_url varchar(255) DEFAULT NULL, 
                file_mahasiswa_url varchar(255) DEFAULT NULL, 
                waktu_review datetime DEFAULT NULL, 
                PRIMARY KEY (id_logbook)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // TABEL NOTIFIKASI
        $this->db->query("
            CREATE TABLE notifikasi (
                id int(11) NOT NULL AUTO_INCREMENT, 
                user_id int(11) NOT NULL, 
                pesan text NOT NULL, 
                link varchar(255) DEFAULT NULL, 
                is_read tinyint(1) DEFAULT 0, 
                created_at datetime DEFAULT CURRENT_TIMESTAMP, 
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        // ==========================================
        // 3. SEEDING DATA DEFAULT (ADMIN)
        // ==========================================
        $hashed_admin = password_hash('admin123', PASSWORD_DEFAULT);
        $this->db->query("INSERT INTO users (username, password, role) VALUES ('admin', '{$hashed_admin}', 'admin');");

        echo "Reset & rebuild database berhasil!\n";
    }
}