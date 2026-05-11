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
        'division_id',
        'unit_id',
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


    public function updateUserInfo($user_id, $data)
    {
        if (!empty($data['division_unit'])) {

            $value = $data['division_unit'];
            $parts = explode('-', $value);
            if (count($parts) === 3) {
                [$type,, $id] = $parts;
                if ($type === 'division') {
                    $data['division_id'] = $id;
                    $data['unit_id'] = null; 
                } elseif ($type === 'unit') {
                    $data['unit_id'] = $id;
                    $data['division_id'] = null;
                }
            }
            unset($data['division_unit']);
        }

        return $this->update($user_id, $data);
    }

    public function useradd(
    string $first_name,
    string $last_name,
    string $email,
    string $password,
    string $position,
    string $salary_grade,
    string $role,
    ?int $division_id,
    ?int $unit_id,
    ?int $organization_id)
    {

    $this->db->transStart();


    $this->db->table('users')->insert([
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' =>  $email,
        'password' => $password,
        'position' =>   $position,
        'salary_grade' => $salary_grade,
        'role' => $role,
        'division_id' => $division_id,
        'unit_id' => $unit_id,
        'organization_id' => $organization_id
    ]);
    $user_id = $this->getInsertID();
            
    $this->db->transComplete();

    return $this->db->transStatus();
    }
}
