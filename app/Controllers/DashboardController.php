<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {    
        $data = [
            'title' => 'ICT Inventory | Dashboard',
            'page' => 'Dashboard',
        ];
        $role = session()->get('role');
        if($role === 'admin'){
            return view('admin/dashboard', $data);
        }
        return view('client/dashboard', $data);
    }
}
