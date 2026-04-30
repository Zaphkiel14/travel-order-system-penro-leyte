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

    Public function dataUserManagement(){
        $draw = (int) ($this->request->getPost('draw') ?? 1);
        $start = (int) ($this->request->getPost('start') ?? 0 );
        $length = (int) ($this->request->getPost('length') ?? 10);
        $search = $this->request->getPost('search')['value'] ?? '';
        $orderCol = (int) ($this->request->getPost('order')[0]['column'] ?? 0);
        $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'desc';

        $columns = [
            0 => 'first_name',
            1 => 'last_name',
            2 => 'email',
            3 => 'position',
            4 => 'salary_grade',
            5 => 'role'
        ];
        $orderBy = $columns[$orderCol] ?? 'first_name';

        $model = new UserModel();

        $total = $model->getUserManagementQuery()
            ->countAllResults(false);
        $query = $model->getUserManagementQuery();
        if ($search !== '') {
            $query->groupStart()
                ->like('first_name' ,$search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->orLike('position',$search)
                ->orLike('salary_grade', $search)
                ->orLike('role',$search)
                ->groupEnd();
        }
        // Filtered count (before pagination)
        $filtered = $query->countAllResults(false);

        // Paginated results
        $rows = $query->orderBy($orderBy, $orderDir)
            ->findAll($length, $start);

        $data = array_map(function ($row) {
            return [
                'first_name' => esc($row['first_name']),
                'last_name' => esc($row['last_name']),
                'email' => esc($row['email']),
                'position' => esc($row['position']),
                'salary_grade' => esc($row['salary_grade']),    
                'role' => esc($row['role']),
                'actions' => '<button type="button"
                                class="btn btn-sm btn-primary btn-view-travel-order"
                                data-id="' . $row['user_id'] . '">
                                <i class="bi bi-eye"></i> View
                                </button>',
            ];
        }, $rows);
        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);

    }

    // public function profile()
    // {
    //     $data = [
    //         'title' => 'Travel Order | Profile',
    //         'page' => 'Profile',
    //     ];
    //     return redirect()->route('profile');
    // }

    // public function updateProfile(){
    //     $data = [
    //         'first_name' => $this->request->getPost('first_name'),
    //         'last_name' => $this->request->getPost('last_name'),
    //         'email' => $this->request->getPost('email'),
    //         'password' => $this->request->getPost('password'),
    //         'position' => $this->request->getPost('position'),
    //     ];


    // }

    //only admin  should be able to use this function
    // public function upgradeAuthority(){
    //     $data = [
    //         'role' => $this->request->getPost('role')

    //     ]
    // }
}
