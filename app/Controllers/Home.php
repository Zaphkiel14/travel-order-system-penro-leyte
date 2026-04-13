<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->route('login');
        }
        $role = $session->get('role');

        if ($role === 'admin') {
            return redirect()->route('dashboard');
        }

        if ($role === 'user') {
            return redirect()->route('dashboard');
        }

        // fallback
        return redirect()->route('login');
    }
}
