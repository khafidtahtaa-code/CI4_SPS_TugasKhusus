<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Halaman Login
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // Jika sudah login, redirect sesuai role
        if (session()->get('username')) {
            return $this->redirectRole(session()->get('role'));
        }

        return view('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Proses Login
    |--------------------------------------------------------------------------
    */
    public function proses_login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db   = \Config\Database::connect();
        $user = $db->query("SELECT * FROM users WHERE username = ?", [$username])->getRowArray();

        // RULES VALIDASI HANYA BERLAKU JIKA: User tidak ditemukan ATAU ditemukan tapi BUKAN admin
        if (!$user || $user['role'] !== 'admin') {
            $rules = [
                'username' => [
                    'rules'  => 'required|numeric',
                    'errors' => [
                        'required'   => 'Username wajib diisi!',
                        'numeric'    => 'Username harus berupa angka murni (NPM/NIDN) anda!',
                        'min_length' => 'Username tidak valid! Pastikan menggunakan jumlah digit NPM/NIDN kampus.'
                    ]
                ],
                'password' => [
                    'rules'  => 'required', // CUKUP REQUIRED SAJA SAAT LOGIN! Hapus regex_match nya.
                    'errors' => [
                        'required'    => 'Password wajib diisi!'
                    ]
                ]
            ];

            // $rules = [

            //     'username' => [
            //         'rules'  => 'required',
            //         'errors' => [
            //             'required' => 'Username wajib diisi!'
            //         ]
            //     ],

            //     'password' => [
            //         'rules'  => 'required',
            //         'errors' => [
            //             'required' => 'Password wajib diisi!'
            //         ]
            //     ]

            // ];

            if (!$this->validate($rules)) {
                return redirect()->to('/')
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }

            if (!$this->validate($rules)) {
                return redirect()->to('/')->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        // ------------------------------------------------------------------
        // PROSES LOGIN ASLI (Admin langsung lolos ke sini tanpa rules di atas)
        // ------------------------------------------------------------------

        // Cek user jika benar-benar tidak ada di DB
        if (!$user) {
            return redirect()->to('/')->with('error', 'User tidak ditemukan');
        }

        // Cek password belum dibuat
        if (empty($user['password'])) {
            return redirect()->to('/')->with('error', 'Silakan buat password terlebih dahulu');
        }

        // Cek password
        if (!password_verify($password, $user['password'])) {
            return redirect()->to('/')->with('error', 'Password salah');
        }

        // Set session
        session()->set([
            'user_id'  => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role'],
        ]);

        return $this->redirectRole($user['role']);
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect berdasarkan role
    |--------------------------------------------------------------------------
    */
    private function redirectRole($role)
    {
        if ($role == 'admin')      return redirect()->to('/admin/dashboard');
        if ($role == 'dosen')      return redirect()->to('/dosen/dashboard');
        if ($role == 'mahasiswa')  return redirect()->to('/mahasiswa/dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | Halaman Buat Password
    |--------------------------------------------------------------------------
    */
    public function password()
    {
        return view('password');
    }

    /*
    |--------------------------------------------------------------------------
    | Proses Buat Password
    |--------------------------------------------------------------------------
    */
    public function proses_password()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db   = \Config\Database::connect();
        $user = $db->query("SELECT * FROM users WHERE username = ?", [$username])->getRowArray();

        // 1. CEK: Apakah user ada?
        if (!$user) {
            return redirect()->to('/password')->with('error', 'Username tidak ditemukan!');
        }

        // 2. [LOGIKA PENGUNCIAN]: Cek apakah password sudah ada?
        // Jika sudah ada, berarti user sudah pernah buat sandi. TOLAK!
        if (!empty($user['password'])) {
            return redirect()->to('/password')->with('error', 'Akun Anda sudah memiliki password. Anda tidak bisa membuat ulang. Silakan hubungi admin untuk reset.');
        }

        // 3. Validasi input password baru
        $rules = [
            'password' => [
                'rules'  => 'required|regex_match[/^(?=.*[a-zA-Z])(?=.*\d).+$/]',
                'errors' => ['regex_match' => 'Format password kurang sesuai! Harus berupa kombinasi huruf dan angka.']
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/password')->withInput()->with('errors', $this->validator->getErrors());
        }

        // 4. Update Password (ini hanya jalan kalau $user['password'] tadi kosong/null)
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $db->query("UPDATE users SET password = ? WHERE username = ?", [$hashed, $username]);

        return redirect()->to('/')->with('success', 'Password berhasil dibuat! Silakan login.');
    }
    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
