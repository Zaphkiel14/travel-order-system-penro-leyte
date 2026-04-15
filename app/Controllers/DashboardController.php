<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\TravelOrderModel;
use App\Models\SelectModel;
use App\Services\GoogleDriveService;



class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Travel Order | Dashboard',
            'page' => 'Dashboard',
        ];
        $role = session()->get('role');
        if ($role === 'admin') {
            return view('admin/dashboard', $data);
        } else if ($role === 'penro') {
            return view('penro/dashboard', $data);
        } else if ($role === 'division_chief') {
            return view('division-chief/dashboard', $data);
        } else if ($role === 'unit-supervisor') {
            return view('unit-supervisor/dashboard', $data);
        } else if ($role === 'employee') {
            return view('client/dashboard', $data);
        } else {
            return redirect()->to('/login');
        }
    }


}
