<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitsModel extends Model
{
    protected $table            = 'units';
    protected $primaryKey       = 'unit_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'division_id',
        'unit_name',
        'unit_head_id',
        'unit_head_position'
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

    
    public function insertDivision(
        int $parent_organization, 
        string $division_name, 
        string $division_head_position, 
        array $linked_units)
    {
        $this->db->transStart();
        $this->insert([
            'organization_id' => $parent_organization, 
            'division_name' => $division_name, 
            'division_head_position' => $division_head_position]);
        $division_id = $this->getInsertID();
        if (!empty($linked_units)) {
            $LinkedUnits = [];
            foreach ($LinkedUnits as $unit_id) {
                $LinkedUnits[] = [
                    'division_id' => $division_id,
                    'unit_id' => $unit_id
                ];
            }
            $this->db->table('units')->insertBatch($LinkedUnits);
        }
        $this->db->transComplete();
        return $this->db->transStatus() ? $division_id : false;
        
    }

    public function insertUnit(
        int $parent_division,
        string $unit_name,
        string $unit_head_position,
        ?int $unit_head
    )
    {
        $this->db->transStart();
        $this->insert([
            'division_id' => $parent_division,
            'unit_name' => $unit_name,
            'unit_head_position' => $unit_head_position,
            'unit_head_id' => $unit_head
        ]);
        $unit_id = $this->getInsertID();
        $this->db->transComplete();
        return $this->db->transStatus() ? $unit_id : false;
    }
      public function getDetails($id)
    {
        $db = \Config\Database::connect();

        $unit = $db->table('units u')
            ->select('
                u.unit_id,
                u.unit_name,
                u.unit_head_position,
                CONCAT(us.first_name, " ", us.last_name) as unit_head_name,
                d.division_name,
                o.organization_name
            ')
            ->join('users us', 'us.user_id = u.unit_head_id', 'left')
            ->join('divisions d', 'd.division_id = u.division_id')
            ->join('organization o', 'o.organization_id = d.organization_id')
            ->where('u.unit_id', $id)
            ->get()->getRow();

        if (!$unit) return null;

        $members = $db->table('users')
            ->select('
                user_id,
                CONCAT(first_name," ",last_name) as full_name,
                position,
                role
            ')
            ->where('unit_id', $id)
            ->get()->getResult();

        return [
            'unit_id' => $unit->unit_id,
            'unit_name' => $unit->unit_name,
            'unit_head_position' => $unit->unit_head_position,
            'unit_head_name' => $unit->unit_head_name,
            'division_name' => $unit->division_name,
            'organization_name' => $unit->organization_name,
            'members' => $members
        ];
    }
}
