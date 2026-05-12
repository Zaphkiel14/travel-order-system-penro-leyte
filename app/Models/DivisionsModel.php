<?php

namespace App\Models;

use CodeIgniter\Model;

class DivisionsModel extends Model
{
    protected $table = 'divisions';
    protected $primaryKey = 'division_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'organization_id',
        'division_name',
        'division_head_id',
        'division_head_position'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function insertDivision(
        int $parent_organization,
        string $division_name,
        string $division_head_position,
        ?int $division_head,
        array $linked_units
    ) {
        $this->db->transStart();
        $this->insert([
            'organization_id' => $parent_organization,
            'division_name' => $division_name,
            'division_head_position' => $division_head_position,
            'division_head_id' => $division_head
        ]);
        $division_id = $this->getInsertID();
        if (!empty($linked_units)) {
            $batch = [];

            foreach ($linked_units as $unit_id) {
                $batch[] = [
                    'unit_id' => $unit_id,
                    'division_id' => $division_id
                ];
            }

            $this->db->table('units')->updateBatch($batch, 'unit_id');
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }
    public function updateDivision(
        int $division_id,
        int $organization_id,
        string $division_name,
        int $division_head_id,
        string $division_head_position
    ) {
        $this->db->transStart();

        $this->update($division_id, [
            'organization_id' => $organization_id,
            'division_name' => $division_name,
            'division_head_id' => $division_head_id,
            'division_head_position' => $division_head_position
        ]);

        $this->db->transComplete();

        return $this->db->transStatus() ? $organization_id : false;
    }

    public function getDetails($id)
    {
        $division = $this->db->table('divisions d')
            ->select('
                d.division_id,
                d.division_name,
                d.division_head_position,
                CONCAT(u.first_name, " ", u.last_name) as division_head_name,
                o.organization_name
            ')
            ->join('users u', 'u.user_id = d.division_head_id', 'left')
            ->join('organization o', 'o.organization_id = d.organization_id')
            ->where('d.division_id', $id)
            ->get()->getRow();

        if (!$division)
            return null;

        $units = $this->db->table('units')
            ->select('unit_id, unit_name')
            ->where('division_id', $id)
            ->get()->getResult();

        $members = $this->db->table('users')
            ->select('
                user_id,
                CONCAT(first_name," ",last_name) as full_name,
                position,
                role
            ')
            ->where('division_id', $id)
            ->get()->getResult();

        return [
            'division_id' => $division->division_id,
            'division_name' => $division->division_name,
            'division_head_position' => $division->division_head_position,
            'division_head_name' => $division->division_head_name,
            'organization_name' => $division->organization_name,
            'units' => $units,
            'members' => $members
        ];
    }
}
