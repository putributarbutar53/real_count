<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    { // Check if the current URL is 'admin2011/login'
        $currentURL = $request->uri->getPath();
        // print_r($currentURL);die();
        // Cek apakah user sudah login

        if (!session()->get('admin_username') && ($currentURL !== 'suara24/login' && $currentURL !== 'suara24/lupapassword' && $currentURL !== 'suara24/resetpassword')) {
            // Jangan redirect jika sudah di halaman login
            if ($request->getUri()->getPath() !== '/suara24/login') {
                return redirect()->to('/suara24/login');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
