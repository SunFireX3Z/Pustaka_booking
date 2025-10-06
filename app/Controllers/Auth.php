<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function index()
    {
        helper(['form']);
        return view('auth/login');
    }
    public function registerForm()
    {
        helper(['form']);
        return view('auth/register');
    }

    public function register()
    {
        helper(['form']);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[user.email]',
            'password' => 'required|min_length[6]',
            'passconf' => 'required|matches[password]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('auth/register', [
                'validation' => $validation
            ]);
        }

        $userModel = new UserModel();
        $userModel->insert([
            'nama'          => $this->request->getPost('nama'),
            'email'         => $this->request->getPost('email'),
            'image'         => 'default.png',
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'       => 3, // Default Member
            'is_active'     => 1,
            'tanggal_input' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')->with('msg', 'Registrasi berhasil, silakan login.');
    }

    public function login()
    {
        $session = session();
        $model   = new UserModel();
        $email   = $this->request->getPost('email');
        $password= $this->request->getPost('password');

        $user = $model->select('user.*, role.role')
                      ->join('role', 'role.id = user.role_id', 'left')
                      ->where('email', $email)
                      ->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $sessionData = [
                    'user_id'   => $user['id'],
                    'nama'      => $user['nama'],
                    'email'     => $user['email'],
                    'image'     => $user['image'],
                    'role_id'   => $user['role_id'],
                    'role'      => $user['role'], // Menambahkan nama peran ke sesi
                    'logged_in' => true
                ];
                $session->set($sessionData);

                // Arahkan berdasarkan role_id
                if ($user['role_id'] == 1 || $user['role_id'] == 2) { // Admin atau Petugas
                    return redirect()->to('/dashboard');
                } else { // Member
                    return redirect()->to('/member');
                }
            } else {
                $session->setFlashdata('msg', 'Password salah');
                return redirect()->back();
            }
        } else {
            $session->setFlashdata('msg', 'Email tidak ditemukan');
            return redirect()->back();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}