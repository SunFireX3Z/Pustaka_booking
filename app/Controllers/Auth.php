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
                if ($user['role_id'] == 1) { // Admin
                    return redirect()->to('/dashboard');
                } elseif ($user['role_id'] == 2) { // Member (sesuai database)
                    return redirect()->to('/member');
                } else { // Jika ada role_id lain yang tidak terdefinisi
                    return redirect()->to('/login')->with('msg', 'Peran pengguna tidak valid.');
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