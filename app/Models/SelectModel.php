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
}
