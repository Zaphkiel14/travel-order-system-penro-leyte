<?php

namespace App\Models;

use CodeIgniter\Model;

class SelectModel extends Model
{
    function generateNextTravelOrderID()
    {
        $year  = date('Y');

        $base = "TO#-{$year}";
        $builder = $this->db->table('travel_orders');

        $builder->select('travel_order_number');
        $builder->like('travel_order_number', $base, 'after');
        $builder->orderBy('travel_order_number', 'DESC');
        $builder->limit(1);

        $result = $builder->get()->getRow();

        if ($result) {
            $parts = explode('-', $result->travel_order_number);
            $lastIncrement = (int) end($parts);
            $nextIncrement = $lastIncrement + 1;
        } else {
            $nextIncrement = 1;
        }

        $nextIncrementFormatted = str_pad($nextIncrement, 4, '0', STR_PAD_LEFT);

        return "{$base}-{$nextIncrementFormatted}";
    }


    public function getCreatorTravelOrderStats(int $userId): array
    {
        return $this->db->table('travel_orders')
            ->select("
        COUNT(*) as total,

        SUM(
            CASE
                WHEN LOWER(current_status) LIKE 'forwarded to%'
                THEN 1
                ELSE 0
            END
        ) as pending,

        SUM(
            CASE
                WHEN LOWER(current_status) LIKE 'approved by%'
                THEN 1
                ELSE 0
            END
        ) as approved,

        SUM(
            CASE
                WHEN LOWER(current_status) LIKE 'rejected by%'
                THEN 1
                ELSE 0
            END
        ) as rejected
    ")
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();
    }


    public function getAssigneeTravelOrderStats(string $level, int $userId): array
    {
        $idColumn = $level . '_id';
        $statusColumn = $level . '_status';

        return $this->db->table('travel_orders')
            ->select("
        COUNT(*) as total,

        SUM(
            CASE
                WHEN {$statusColumn} = 'pending'
                THEN 1
                ELSE 0
            END
        ) as pending,

        SUM(
            CASE
                WHEN {$statusColumn} = 'approved'
                THEN 1
                ELSE 0
            END
        ) as approved,

        SUM(
            CASE
                WHEN {$statusColumn} = 'rejected'
                THEN 1
                ELSE 0
            END
        ) as rejected
    ")
            ->where($idColumn, $userId)
            ->get()
            ->getRowArray();
    }

    public function organizationStructure()
    {
        $orgBuilder = $this->db->table('organization');
        $organization = $orgBuilder
            ->where('organization_id', 1)
            ->get()
            ->getRow();

        $divBuilder = $this->db->table('divisions');
        $organization->divisions = $divBuilder
            ->where('organization_id', $organization->organization_id)
            ->get()
            ->getResult();

        foreach ($organization->divisions as $division) {
            $unitBuilder = $this->db->table('units');
            $division->units = $unitBuilder
                ->where('division_id', $division->division_id)
                ->get()
                ->getResult();
        }
        return $organization;
    }

    public function selectOrganization()
    {
        $builder = $this->db->table('organization');
        $builder->select('organization_id, organization_name');
        $builder->limit('1');
        return $builder->get()->getResult();
    }
    public function selectDivisions()
    {
        $builder = $this->db->table('divisions');
        $builder->select('division_id, division_name');
        return $builder->get()->getResult();
    }

    public function selectUnits()
    {
        $builder = $this->db->table('units');
        $builder->select('unit_id, unit_name');
        return $builder->get()->getResult();
    }

    public function selectDivisionUnit()
    {
        $orgBuilder = $this->db->table('organization');
        $organization = $orgBuilder
            ->select("'organization' as type, organization_id as id, organization_name as name")
            ->get()
            ->getResultArray();
        // Select divisions
        $divBuilder = $this->db->table('divisions');
        $divisions = $divBuilder
            ->select("'division' as type, division_id as id, division_name as name")
            ->get()
            ->getResultArray();

        // Select units
        $unitBuilder = $this->db->table('units');
        $units = $unitBuilder
            ->select("'unit' as type, unit_id as id, unit_name as name")
            ->get()
            ->getResultArray();

        // Merge both
        $results = array_merge($organization, $divisions, $units);

        return $results;
    }


    public function resolveHierarchy(string $type, int $id): array
    {
        $result = [
            'unit_id'         => null,
            'division_id'     => null,
            'organization_id' => null,
            'current_level' => null,
        ];

        if ($type === 'unit') {
            $unit = $this->db->table('units u')
                ->select('u.unit_id, u.unit_name , d.division_id, d.organization_id')
                ->join('divisions d', 'd.division_id = u.division_id')
                ->where('u.unit_id', $id)
                ->get()
                ->getRow();

            if ($unit) {
                $result['current_level'] = 'unit';
                $result['current_status'] = 'Forwarded to ' . $unit->unit_name;
                $result['unit_id']         = $unit->unit_id;
                $result['division_id']     = $unit->division_id;
                $result['organization_id'] = $unit->organization_id;
            }
        } elseif ($type === 'division') {
            $division = $this->db->table('divisions')
                ->select('division_id, division_name, organization_id')
                ->where('division_id', $id)
                ->get()
                ->getRow();

            if ($division) {
                $result['current_level'] = 'division';
                $result['current_status'] = 'Forwarded to ' . $division->division_name;
                $result['division_id']     = $division->division_id;
                $result['organization_id'] = $division->organization_id;
            }
        } elseif ($type === 'organization') {
            $result['current_level'] = 'organization';
            $result['current_status'] = 'Forwarded to PENRO';
            $result['organization_id'] = $id;
        }

        return $result;
    }

    public function getManagedByRole(string $role, int $userId)
    {
        switch ($role) {
            case 'unit':
                return $this->db->table('units')
                    ->select('unit_id as id, unit_name as name')
                    ->where('unit_head_id', $userId)
                    ->get()
                    ->getRow();
            case 'division':
                return $this->db->table('divisions')
                    ->select('division_id as id, division_name as name')
                    ->where('division_head_id', $userId)
                    ->get()
                    ->getRow();
            case 'penro':
                return $this->db->table('organization')
                    ->select('organization_id as id, organization_name as name')
                    ->where('organization_head_id', $userId)
                    ->get()
                    ->getRow();
            default:
                return null;
        }
    }

    public function getDivisionById(int $divisionId)
    {
        return $this->db->table('divisions')
            ->select('division_id, division_name')
            ->where('division_id', $divisionId)
            ->get()
            ->getRowArray();
    }
    public function getOrganizationById(int $organizationId)
    {
        return $this->db->table('organization')
            ->select('organization_id, organization_name')
            ->where('organization_id', $organizationId)
            ->get()
            ->getRowArray();
    }
    public function getUnitById(int $unitId)
    {
        return $this->db->table('units')
            ->select('unit_id, unit_name')
            ->where('unit_id', $unitId)
            ->get()
            ->getRowArray();
    }

    public function getUserManagementSummary(int $userId, string $role): array
    {
        $db = $this->db;
        $user = $db->table('users')
            ->select('
            user_id, 
            CONCAT(first_name, " ", last_name) as full_name,
            email,
            position,
            role')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();
        $managed = $this->getManagedByRole($role, $userId);

        $structure = [
            'unit' => null,
            'division' => null,
            'organization' => null,
        ];

        if ($role === 'unit' && $managed) {
            $structure['unit'] = $managed;
        }

        if ($role === 'division' && $managed) {
            $structure['division'] = $managed;
        }

        if ($role === 'penro' && $managed) {
            $structure['organization'] = $managed;
        }

        // 3. Get travel order stats (pending, approved, rejected)
        $stats = $this->getAssigneeTravelOrderStats($role, $userId);

        return [
            'user' => $user,
            'managed_structure' => $structure,
            'travel_order_stats' => $stats
        ];
    }


    public function getAllPendingReminderRecipients(): array
    {

        $unit = $this->db->table('travel_orders t')
            ->select('
            u.user_id,
            CONCAT(u.first_name, " ", u.last_name) as full_name,
            u.email,
            u.position,
            un.unit_name as managed_unit_div_org,
            COUNT(t.travel_order_id) as pending_count
        ')
            ->join('units un', 'un.unit_id = t.unit_id')
            ->join('users u', 'u.user_id = un.unit_head_id AND u.role = "unit"')
            ->where('t.current_level', 'unit')
            ->where('t.unit_status', 'pending')
            ->groupBy('un.unit_id')
            ->getCompiledSelect();

        $division = $this->db->table('travel_orders t')
            ->select('
            u.user_id,
            CONCAT(u.first_name, " ", u.last_name) as full_name,
            u.email,
            u.position,
            d.division_name as managed_unit_div_org,
            COUNT(t.travel_order_id) as pending_count
        ')
            ->join('divisions d', 'd.division_id = t.division_id')
            ->join('users u', 'u.user_id = d.division_head_id AND u.role = "division"')
            ->where('t.current_level', 'division')
            ->where('t.division_status', 'pending')
            ->groupBy('d.division_id')
            ->getCompiledSelect();

        $org = $this->db->table('travel_orders t')
            ->select('
            u.user_id,
            CONCAT(u.first_name, " ", u.last_name) as full_name,
            u.email,
            u.position,
            o.organization_name as managed_unit_div_org,
            COUNT(t.travel_order_id) as pending_count
        ')
            ->join('organization o', 'o.organization_id = t.organization_id')
            ->join('users u', 'u.user_id = o.organization_head_id AND u.role = "penro"')
            ->where('t.current_level', 'organization')
            ->where('t.organization_status', 'pending')
            ->groupBy('o.organization_id')
            ->getCompiledSelect();

        $query = $this->db->query("
        {$unit}
        UNION ALL
        {$division}
        UNION ALL
        {$org}
    ");

        return $query->getResultArray();
    }


    public function getUserPendingSummary(int $userId, string $role)
    {
        $managed = $this->getManagedByRole($role, $userId);

        if (!$managed) {
            return null;
        }

        switch ($role) {
            case 'unit':
                $column = 'unit_id';
                $statusColumn = 'unit_status';
                break;

            case 'division':
                $column = 'division_id';
                $statusColumn = 'division_status';
                break;

            case 'penro':
                $column = 'organization_id';
                $statusColumn = 'organization_status';
                break;

            default:
                return null;
        }

        $count = $this->db->table('travel_orders')
            ->where('current_level', $role)
            ->where($statusColumn, 'pending')
            ->where($column, $managed->id)
            ->countAllResults();

        return [
            'user_id' => $userId,
            'managed_id' => $managed->id,
            'managed_name' => $managed->name,
            'role' => $role,
            'pending_count' => $count
        ];
    }

    public function getProcessedByScopeQuery(string $level, int $id)
    {
        return $this->select('
            travel_order_id,
            travel_order_number,
            departure_date,
            arrival_date,
            destination,
            purpose_of_travel,
            current_status,
            created_at
        ')
            ->where($level . '_id', $id)
            ->whereIn($level . '_status', ['approved', 'rejected']);
    }


    public function getAllTravelOrderStats()
    {
        return $this->db->table('travel_orders')
            ->select("
        COUNT(*) as total,

        SUM(
            CASE
                WHEN LOWER(current_status) LIKE 'forwarded to%'
                THEN 1
                ELSE 0
            END
        ) as pending,

        SUM(
            CASE
                WHEN LOWER(current_status) LIKE 'approved by%'
                THEN 1
                ELSE 0
            END
        ) as approved,

        SUM(
            CASE
                WHEN LOWER(current_status) LIKE 'rejected by%'
                THEN 1
                ELSE 0
            END
        ) as rejected
    ")
            ->get()
            ->getRowArray();
    }

    public function getTravelOrderStatsByDate($month = null, $year = null)
    {
        $builder = $this->db->table('travel_orders');

        $builder->select("
        DATE(created_at) as date,
        COUNT(*) as total,
        SUM(CASE WHEN LOWER(current_status) LIKE 'forwarded to%' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN LOWER(current_status) LIKE 'approved by%' THEN 1 ELSE 0 END) as approved,
        SUM(CASE WHEN LOWER(current_status) LIKE 'rejected by%' THEN 1 ELSE 0 END) as rejected
    ");

        if ($year)  $builder->where('YEAR(created_at)', $year);
        if ($month) $builder->where('MONTH(created_at)', $month);

        $builder->groupBy('DATE(created_at)')->orderBy('DATE(created_at)', 'ASC');

        return $builder->get()->getResultArray();
    }


    public function getUnitUsers()
    {
        return $this->db->table('users u')
            ->select('
            u.user_id,
            CONCAT(u.first_name, " ", u.last_name) as full_name,
            un.unit_name
        ')
            ->select("
            CASE 
                WHEN un.unit_head_id IS NOT NULL THEN 1 
                ELSE 0 
            END as is_managing_unit
        ", false)
            ->join('units un', 'un.unit_head_id = u.user_id', 'left')
            ->where('u.role', 'unit')
            ->get()
            ->getResult();
    }
    public function getDivisionUsers()
    {
        return $this->db->table('users u')
            ->select('
            u.user_id,
            CONCAT(u.first_name, " ", u.last_name) as full_name,
            d.division_name
            ')
            ->select("
            CASE 
                WHEN d.division_head_id IS NOT NULL THEN 1
                ELSE 0
            END as is_managing_division
        ", false)
            ->join('divisions d', 'd.division_head_id = u.user_id', 'left')
            ->where('u.role', 'division')
            ->get()
            ->getResult();
    }
    public function getPenroUsers() 
    {
        return $this->db->table('users u')
            ->select('
            u.user_id, 
            CONCAT(u.first_name, " ", u.last_name) as full_name,
            o.organization_name,
            ')
            ->select("
            CASE 
                WHEN o.organization_head_id IS NOT NULL THEN 1
                ELSE 0
            END as is_managing_organization
            ", false)
            ->join('organization o', 'o.organization_head_id = u.user_id', 'left')
            ->where('u.role', 'penro')
            ->get()
            ->getResult();
    }

    public function getOrganizationData(){
        return $this->db->table('organization')
            ->select('organization_id, organization_name, organization_head_position, organization_head_id')
            ->where('organization_id', 1)
            ->get()
            ->getRowArray();
    }
}
