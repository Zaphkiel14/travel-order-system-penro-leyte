<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function settings()
    {
        // Get user data from database
        $userModel = new UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);
        $data = [
            'title' => 'Travel Order | Settings',
            'page' => 'Account Settings',
            'user' => $user,
        ];
        return view('account-settings', $data);
    }

    public function updateAccountInfo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $data = $this->request->getJSON(true);
        $field = $data['field'] ?? null;
        $value = $data['value'] ?? null;

        if (!$field) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Field is required'
            ]);
        }

        // Validate allowed fields
        $allowedFields = ['first_name', 'last_name', 'email', 'birthdate', 'gender'];
        if (!in_array($field, $allowedFields)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid field'
            ]);
        }

        // Validate email if field is email
        if ($field === 'email') {
            $validation = \Config\Services::validation();
            if (!$validation->check($value, 'valid_email')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid email format'
                ]);
            }

            // Check if email is already taken by another user
            $userModel = new UserModel();
            $existingUser = $userModel->where('email', $value)
                ->where('user_id !=', $userId)
                ->first();

            if ($existingUser) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email is already taken'
                ]);
            }
        }

        // Update user info
        $userModel = new UserModel();
        $updateData = [$field => $value];

        if ($userModel->update($userId, $updateData)) {
            // Update session if first_name or last_name changed
            if ($field === 'first_name' || $field === 'last_name') {
                $updatedUser = $userModel->find($userId);
                session()->set([
                    'first_name' => $updatedUser['first_name'],
                    'last_name' => $updatedUser['last_name'],
                    'full_name' => $updatedUser['first_name'] . ' ' . $updatedUser['last_name']
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $field)) . ' updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update account information'
            ]);
        }
    }

    public function updateProfilePicture()
    {
        $respond = function (array $payload) {
            $payload['csrf_token'] = csrf_hash();
            return $this->response->setJSON($payload);
        };

        if (!$this->request->isAJAX()) {
            return $respond([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $respond([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $file = $this->request->getFile('profile_picture');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $respond([
                'success' => false,
                'message' => 'No valid file uploaded'
            ]);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return $respond([
                'success' => false,
                'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.'
            ]);
        }

        // Validate file size (5MB max)
        if ($file->getSize() > 5 * 1024 * 1024) {
            return $respond([
                'success' => false,
                'message' => 'File size must be less than 5MB'
            ]);
        }

        // Get current user to check for existing profile picture
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Delete old profile picture if it exists
        if (!empty($user['profile_picture']) && file_exists(FCPATH . $user['profile_picture'])) {
            // Only delete if it's not the default picture
            if (strpos($user['profile_picture'], 'uploads/profile_pictures/') !== false) {
                unlink(FCPATH . $user['profile_picture']);
            }
        }

        // Ensure uploads/profile_pictures directory exists in public folder
        $uploadPath = FCPATH . 'uploads/profile_pictures/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $newName = $file->getRandomName();

        // Move file to uploads directory
        if ($file->move($uploadPath, $newName)) {
            $profilePicturePath = 'uploads/profile_pictures/' . $newName;

            // Update user profile picture in database
            if ($userModel->update($userId, ['profile_picture' => $profilePicturePath])) {
                // Update session
                session()->set('profile_picture', $profilePicturePath);

                return $respond([
                    'success' => true,
                    'message' => 'Profile picture updated successfully',
                    'profile_picture_url' => base_url($profilePicturePath)
                ]);
            } else {
                // If database update fails, delete the uploaded file
                if (file_exists($uploadPath . $newName)) {
                    unlink($uploadPath . $newName);
                }
                return $respond([
                    'success' => false,
                    'message' => 'Failed to update profile picture in database'
                ]);
            }
        } else {
            return $respond([
                'success' => false,
                'message' => 'Failed to upload file: ' . $file->getErrorString()
            ]);
        }
    }

    public function changePassword()
    {
        $respond = function (array $payload) {
            $payload['csrf_token'] = csrf_hash();
            return $this->response->setJSON($payload);
        };

        if (!$this->request->isAJAX()) {
            return $respond([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $respond([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $data = $this->request->getJSON(true);
        $currentPassword = $data['current_password'] ?? '';
        $newPassword = $data['new_password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        // Validate inputs
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            return $respond([
                'success' => false,
                'message' => 'All password fields are required'
            ]);
        }

        if (strlen($newPassword) < 8) {
            return $respond([
                'success' => false,
                'message' => 'New password must be at least 8 characters long'
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            return $respond([
                'success' => false,
                'message' => 'New passwords do not match'
            ]);
        }

        // Verify current password
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return $respond([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        if (!password_verify($currentPassword, $user['password'])) {
            return $respond([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);
        }

        if (password_verify($newPassword, $user['password'])) {
            return $respond([
                'success' => false,
                'message' => 'New password must be different from current password'
            ]);
        }

        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        if ($userModel->update($userId, ['password' => $hashedPassword])) {
            return $respond([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);
        } else {
            return $respond([
                'success' => false,
                'message' => 'Failed to change password'
            ]);
        }
    }

    public function deleteAccount()
    {
        $respond = function (array $payload) {
            $payload['csrf_token'] = csrf_hash();
            return $this->response->setJSON($payload);
        };

        if (!$this->request->isAJAX()) {
            return $respond([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $respond([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        $data = $this->request->getJSON(true);
        $password = $data['password'] ?? '';

        if (empty($password)) {
            return $respond([
                'success' => false,
                'message' => 'Password is required'
            ]);
        }

        // Verify password
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return $respond([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return $respond([
                'success' => false,
                'message' => 'Password is incorrect'
            ]);
        }

        if (!$userModel->deleteAccount($userId)) {
            return $respond([
                'success' => false,
                'message' => 'Failed to delete account. Please try again later.'
            ]);
        }

        // Destroy session after successful deletion
        session()->destroy();

        return $respond([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    }

    public function updateFirstName()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to update your profile.');
        }

        $firstName = trim((string) $this->request->getPost('first_name'));

        if ($firstName === '') {
            return redirect()->back()->with('error', 'First name is required.');
        }

        $userModel = new UserModel();
        if ($userModel->updateFirstName($userId, $firstName)) {
            session()->set('first_name', $firstName);
            $fullName = trim($firstName . ' ' . (session('last_name') ?? ''));
            session()->set('full_name', $fullName);
            return redirect()->back()->with('success', 'First name updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update first name. Please try again.');
    }

    public function updateLastName()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to update your profile.');
        }

        $lastName = trim((string) $this->request->getPost('last_name'));

        if ($lastName === '') {
            return redirect()->back()->with('error', 'Last name is required.');
        }

        $userModel = new UserModel();
        if ($userModel->updateLastName($userId, $lastName)) {
            session()->set('last_name', $lastName);
            $fullName = trim((session('first_name') ?? '') . ' ' . $lastName);
            session()->set('full_name', $fullName);
            return redirect()->back()->with('success', 'Last name updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update last name. Please try again.');
    }

    public function updateEmail()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to update your profile.');
        }

        $email = trim((string) $this->request->getPost('email'));
        $currentPassword = (string) $this->request->getPost('current_password');
        $validation = \Config\Services::validation();

        if (!$validation->check($email, 'required|valid_email')) {
            return redirect()->back()->with('error', 'Please provide a valid email address.');
        }

        if ($currentPassword === '') {
            return redirect()->back()->with('error', 'Please enter your password to confirm the change.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'The password you entered is incorrect.');
        }

        $existingUser = $userModel->where('email', $email)->where('user_id !=', $userId)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'This email is already in use.');
        }

        if ($userModel->updateEmail($userId, $email)) {
            return redirect()->back()->with('success', 'Email address updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update email. Please try again.');
    }

    public function updateBirthday()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to update your profile.');
        }

        $birthdate = $this->request->getPost('birthdate');
        if ($birthdate && strtotime($birthdate) > time()) {
            return redirect()->back()->with('error', 'Birthdate cannot be in the future.');
        }

        $userModel = new UserModel();
        if ($userModel->updateBirthdate($userId, $birthdate)) {
            return redirect()->back()->with('success', 'Birthdate updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update birthdate. Please try again.');
    }

    public function updateGender()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to update your profile.');
        }

        $gender = $this->request->getPost('gender');
        $allowedValues = ['Male', 'Female', 'Other', 'Prefer not to say'];

        if (!in_array($gender, $allowedValues, true)) {
            return redirect()->back()->with('error', 'Invalid gender selection.');
        }

        $userModel = new UserModel();
        if ($userModel->updateGender($userId, $gender)) {
            return redirect()->back()->with('success', 'Gender updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update gender. Please try again.');
    }
    
}
