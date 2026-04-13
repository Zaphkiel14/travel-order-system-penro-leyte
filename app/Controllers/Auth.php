<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use Config\Services;
use App\Models\UserModel;
use App\Models\GoogleAccountsModel;
use App\Services\GoogleDriveService;

class Auth extends BaseController
{

    public function logIn()
    {
        return view('auth/login');
    }
    public function auth()
    {
        $session = session();
        $userModel = new UserModel();
        $googleAccountModel = new GoogleAccountsModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        log_message('debug', 'Login attempt with email: ' . $email);

        //search database if email exists
        $user = $userModel->where('email', $email)->first();

        // No user found block 
        if (!$user) {
            log_message('debug', 'No user found with that email.');
            $alert = session()->getFlashdata('alert') ?? [];
            $alert[] = [
                'type' => 'danger',
                'title' => 'Validation Error',
                'message' => 'No User Found with that email.'
            ];

            session()->setFlashdata('alert', $alert);
            return redirect()->back()->withInput();
        }

        // user found but wrong password block
        if (!password_verify($password, $user['password'])) {
            log_message('debug', 'Password verification failed.');
            $alert = session()->getFlashdata('alerts') ?? [];
            $alert[] = [
                'type' => 'danger',
                'title' => 'Password Validation Failed',
                'message' => 'incorrect password try again.'
            ];

            session()->setFlashdata('alert', $alert);
            return redirect()->back()->withInput();
        }



        $session->set([
            'user_id'    => $user['user_id'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'full_name'  => $user['first_name'] . ' ' . $user['last_name'],
            'role'  => $user['role'],
            'position'  => $user['position'],
            'created_at' => $user['created_at'],
            'field_office_id' => $user['field_office_id'],
            'isLoggedIn' => true,
        ]);


        //  initialize alerts array for flashdata
        $alerts = session()->getFlashdata('alerts') ?? [];

        // Welcome alert
        $alerts[] = [
            'type'    => 'success',
            'title'   => 'Welcome Back ' . $user['first_name'] . '!',
            'message' => 'You have successfully logged in.'
        ];
        $googleAccount = $googleAccountModel->where('user_id', $user['user_id'])->first();

        if ($googleAccount) {
            try {
                $drive = new GoogleDriveService();
                $drive->setUser($user['user_id']);
                log_message('debug', 'Google Drive auto-connected.');
                $alerts[] = [
                    'type'    => 'success',
                    'title'   => 'Google Drive Connected',
                    'message' => 'You have successfully connected your Google Drive account.'
                ];
            } catch (\Exception $e) {

                log_message('error', 'Google Drive failed: ' . $e->getMessage());
                // Google Drive issue reconnect issue
                $alerts[] = [
                    'type'    => 'warning',
                    'title'   => 'Google Drive Issue',
                    'message' => 'Logged in, but no access to Google Drive (Cannot insert or retrieve images). Re-log in with Google account to resume access.'
                ];
            }
        } else {
            log_message('debug', 'No Google account linked for this user.');
            $alerts[] = [
                'type'    => 'warning',
                'title'   => 'Google Drive Issue',
                'message' => 'No Google account linked for this user.'
            ];
        }

        session()->setFlashdata('alerts', $alerts);
        // Debug: Log session data to verify it's set
        log_message('debug', 'Session data after setting: ' . json_encode($session->get()));

        // Role-based redirect
        if ($user['role'] === 'admin' || $user['role'] === 'user') {
            log_message('debug', 'Redirecting to dashboard.');
            return redirect()->to(route_to('dashboard'));
        } else {
            log_message('debug', 'Unauthorized role.');
            // Goes back to login
            $alert = [
                'type'    => 'danger',
                'title'   => 'Access Denied',
                'message' => 'Your account has no access.'
            ];

            session()->setFlashdata('alert', $alert);
            return redirect()->route('login');
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    public function register()
    {
        $validation = Services::validation();
        $validation->setRules([
            'first_name'       => 'required|min_length[2]',
            'last_name'        => 'required|min_length[2]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'position'         => 'required',
            'role'             => 'required',
            'field_office'     => 'required',
        ]);

        // Validation check
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();

            // Get old flash alerts or empty array
            $alerts = session()->getFlashdata('alerts') ?? [];

            // Add new validation alert
            $alerts[] = [
                'type'    => 'danger',
                'title'   => 'Validation Error',
                'message' => implode('<br>', $errors)
            ];

            // Save back as flash data
            session()->setFlashdata('alerts', $alerts);

            return redirect()->back()->withInput();
        }

        // Prepare user data
        $rawPassword  = $this->request->getPost('password');
        $passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);

        $data = [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'password'     => $passwordHash,
            'role'         => $this->request->getPost('role'),
            'position'     => $this->request->getPost('position'),
            'field_office' => $this->request->getPost('field_office'),
        ];

        // Save user
        $model  = new UserModel();
        $result = $model->useradd(
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['position'],
            $data['role'],
            $data['field_office'],
        );

        // Add success/failure alert
        $alerts = session()->getFlashdata('alerts') ?? [];
        $alerts[] = [
            'type'    => $result ? 'success' : 'danger',
            'title'   => $result ? 'Account Creation' : 'Error!',
            'message' => $result ? 'User registered successfully.' : 'Failed to register user.'
        ];
        session()->setFlashdata('alerts', $alerts);

        return redirect()->back();
    }

    public function googleLogin()
    {
        $drive = new GoogleDriveService();
        return redirect()->to($drive->getAuthUrl());
    }

    public function callback()
    {
        $code = $this->request->getGet('code');
        $drive = new GoogleDriveService();
        $userModel = new UserModel();
        try {
            $userId = $drive->handleCallback($code);
            $user = $userModel->find($userId);
            session()->set([
                'user_id'    => $user['user_id'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'full_name'  => $user['first_name'] . ' ' . $user['last_name'],
                'role'       => $user['role'],
                'position'   => $user['position'],
                'created_at' => $user['created_at'],
                'field_office_id' => $user['field_office_id'],
                'isLoggedIn' => true,
            ]);
            log_message('debug', 'Google login session: ' . json_encode(session()->get()));
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('alert', [
                'type' => 'danger',
                'title' => 'Google Login Failed',
                'message' => $e->getMessage()
            ]);
        }
    }
}
