<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'password',
        'position',
        'salary_grade',
        'role',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function getUserManagementQuery()
    {
        return $this->select('
            user_id, 
            first_name,
            last_name,
            email,
            position,
            salary_grade,
            role
        ');
    }


    function updateFirstName($user_id, $first_name)
    {
        return $this->update($user_id, ['first_name' => $first_name]);
    }

    function updateLastName($user_id, $last_name)
    {
        return $this->update($user_id, ['last_name' => $last_name]);
    }
    function updateEmail($user_id, $email)
    {
        return $this->update($user_id, ['email' => $email]);
    }
    function updateProfilePicture($user_id, $profile_picture)
    {
        return $this->update($user_id, ['profile_picture' => $profile_picture]);
    }

    function updatePassword($user_id, $old_password, $newPassword)
    {
        // Retrieve user
        $user = $this->find($user_id);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        // Verify old password
        if (!password_verify($old_password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Current password is incorrect'
            ];
        }

        // Hash new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password
        $update = $this->update($user_id, ['password' => $newPasswordHash]);
        if ($update) {
            return [
                'success' => true,
                'message' => 'Password updated successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to update password'
            ];
        }
    }

    public function deleteAccount(int $userId): bool
    {
        return $this->delete($userId);
    }
}
