<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MemberFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        // Jika tidak ada sesi isLoggedIn, lempar ke halaman login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('msg', 'Anda harus login terlebih dahulu.');
        }

        // Jika peran bukan Member (3), lempar ke halaman admin
        if ($session->get('role_id') != 2) { // Hanya izinkan Member (role_id = 2)
            return redirect()->to('/dashboard')->with('error', 'Halaman ini hanya untuk member.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi yang perlu dilakukan setelah request
    }
}