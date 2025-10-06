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
        // Jika tidak ada sesi logged_in, lempar ke halaman login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('msg', 'Anda harus login terlebih dahulu.');
        }

        // Jika peran bukan Member (3), lempar ke halaman admin
        if ($session->get('role_id') != 3) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi yang perlu dilakukan setelah request
    }
}