<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        // Jika tidak ada sesi logged_in, lempar ke halaman login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('msg', 'Anda harus login terlebih dahulu.');
        }

        // Jika peran bukan Administrator (1) atau Petugas (2), lempar ke halaman member
        $role_id = $session->get('role_id');
        if ($role_id != 1) { // Hanya izinkan Administrator
            return redirect()->to('/member')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi yang perlu dilakukan setelah request
    }
}