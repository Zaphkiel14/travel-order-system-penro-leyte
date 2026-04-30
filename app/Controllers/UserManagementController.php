<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;


class UserManagementController extends BaseController
{
    public function index()
    {
        log_message('debug', 'redirecting to user management');
        if (!session()->get('isLoggedIn')) {
            return $this->renderError(
                $this->errorHandler->unauthorized()
            );
        }
        if (session()->get('role') !== "admin") {
            return $this->renderError(
                $this->errorHandler->forbidden('Admins only.')
            );
        }
        $data = [
            'title'        => 'Travel Order | User-Management',
            'page'         => 'User Management',
        ];

        return view('admin/user-management', $data);
    }

    public function profile()
    {
        $data = [
            'title' => 'Travel Order | Profile',
            'page' => 'Profile',
        ];
        return redirect()->route('profile');
    }

    public function updateProfile(){
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'position' => $this->request->getPost('position'),
        ];


    }

    //only admin  should be able to use this function
    // public function upgradeAuthority(){
    //     $data = [
    //         'role' => $this->request->getPost('role')

    //     ]
    // }
}
