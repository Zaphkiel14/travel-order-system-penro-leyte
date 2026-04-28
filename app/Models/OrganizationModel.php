<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationModel extends Model
{
    protected $table            = 'organizations';
    protected $primaryKey       = 'organization_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'organization_id',
        'organization_name',
        'organization_head_id',
        'organization_head_position'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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



    public function insertOrganization(
        string $organization_name,
        string $organization_head_position
    ){
        $this->db->transStart();

        $this->insert([
            'organization_name' => $organization_name,
            'organization_head_position' => $organization_head_position
        ]);
        $organizationId = $this->getInsertID();

        $this->db->transComplete();

        return $this->db->transStatus() ? $organizationId : false;
    }
    public function updateOrganization(
        int $organization_id,
        string $organization_name,
        string $organization_head_position
    ){
        $this->db->transStart();

        $this->update($organization_id, [
            'organization_name' => $organization_name,  
            'organization_head_position' => $organization_head_position]);

        $this->db->transComplete();
        
        return $this->db->transStatus() ? $organization_id : false;

    }
}
