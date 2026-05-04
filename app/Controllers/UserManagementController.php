<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\SelectModel;

use Config\Services;

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

        $model = new SelectModel();
        $data = [
            'title'        => 'Travel Order | User-Management',
            'page'         => 'User Management',
            'divunits' => $model->selectDivisionUnit()
        ];

        return view('admin/user-management', $data);
    }

    public function dataUserManagement()
    {
        $draw = (int) ($this->request->getPost('draw') ?? 1);
        $start = (int) ($this->request->getPost('start') ?? 0);
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
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->orLike('position', $search)
                ->orLike('salary_grade', $search)
                ->orLike('role', $search)
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
                                class="btn btn-primary btn-view-user-entry"
                                data-id="' . $row['user_id'] . '">
                                <i class="bi bi-eye"></i>
                                </button>
                                <button type="button"
                                class="btn btn-success btn-edit-user-entry"
                                data-id="' . $row['user_id'] . '">
                                <i class="bi bi-pencil-square"></i>
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


    public function detailsUserManagement(int $userId)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->renderError(
                $this->errorHandler->unauthorized()
            );
        }

        if (!$this->request->isAJAX()) {
            return $this->renderError(
                $this->errorHandler->forbidden('You are Accessing a Restricted Route.')
            );
        }

        $role = session()->get('role');

        if ($role !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Access denied.',
            ]);
        }

        $model = new UserModel();
        $user  = $model->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }

        // Strip sensitive data before sending
        unset($user['password'], $user['deleted_at']);

        // Resolve profile picture URL
        $profilePicture = $user['profile_picture'] ?? null;
        $user['profile_picture_url'] = ($profilePicture && file_exists(FCPATH . $profilePicture))
            ? base_url($profilePicture)
            : base_url('defaultProfile.jpg');

        return $this->response->setJSON([
            'success' => true,
            'data'    => $user,
        ]);
    }

    public function updateUserManagement($user_id = null)
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request.'
            ]);
        }

        if (!$user_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID is required.'
            ]);
        }

        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name'  => 'required|min_length[2]',
            'email'      => "required|valid_email|is_unique[users.email,user_id,{$user_id}]",
            'position'   => 'required',
            'role'       => 'required|in_list[employee,supervisor,division_head,penro,records,admin]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $validation->getErrors()
            ]);
        }

        $userModel = new UserModel();

        $data = [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'position'     => $this->request->getPost('position'),
            'salary_grade' => $this->request->getPost('salary_grade'),
            'role'         => $this->request->getPost('role'),
            'division_unit' =>  $this->request->getPost('division_unit')
        ];

        // ── Handle Profile Picture Upload ─────────────────────────────
        $file = $this->request->getFile('profile_picture');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $newName = $file->getRandomName();

            if ($file->move(FCPATH . 'uploads/profile_pictures', $newName)) {
                $data['profile_picture'] = $newName;

                // (Optional) delete old image
                $oldUser = $userModel->find($user_id);
                if (!empty($oldUser['profile_picture'])) {
                    $oldPath = FCPATH . 'uploads/profile_pictures/' . $oldUser['profile_picture'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }
        }

        // ── Update user ───────────────────────────────────────────────
        if (!$userModel->updateUserInfo($user_id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update user.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User updated successfully.'
        ]);
    }

    public function registerUser()
    {
        $validation = Services::validation();

        $validation->setRules([
            'first_name'       => 'required|min_length[2]',
            'last_name'        => 'required|min_length[2]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'position'         => 'required',
            'salary_grade'     => 'required',
            'role'             => 'required',
            'division_unit'    => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('toast', [
                'type'    => 'danger',
                'message' => implode('<br>', $validation->getErrors()),
            ]);
        }

        $division_id = null;
        $unit_id     = null;

        $divisionUnit = $this->request->getPost('division_unit');

        if (!empty($divisionUnit)) {
            $parts = explode('-id-', $divisionUnit);

            if (count($parts) === 2) {
                [$type, $id] = $parts;

                if ($type === 'division') {
                    $division_id = $id;
                } elseif ($type === 'unit') {
                    $unit_id = $id;
                }
            }
        }

        $passwordHash = password_hash(
            $this->request->getPost('password'),
            PASSWORD_DEFAULT
        );

        $data = [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'password'     => $passwordHash,
            'position'     => $this->request->getPost('position'),
            'salary_grade' => $this->request->getPost('salary_grade'),
            'role'         => $this->request->getPost('role'),
            'division_id'  => $division_id,
            'unit_id'      => $unit_id,
        ];

        $model  = new UserModel();
        $result = $model->useradd(
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['position'],
            $data['salary_grade'],
            $data['role'],
            $data['division_id'],
            $data['unit_id']
        );

        return redirect()->back()->with('toast', [
            'type'    => $result ? 'success' : 'danger',
            'message' => $result
                ? "User '{$data['email']}' registered successfully."
                : "Failed to register '{$data['email']}'.",
        ]);
    }
}
