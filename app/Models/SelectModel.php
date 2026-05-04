<?php

namespace App\Models;

use CodeIgniter\Model;

class SelectModel extends Model
{
    function generateNextTravelOrderID()
    {
        $year  = date('Y');

        $base = "Travel-Order#-{$year}";

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

    public function selectOrganization(){
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

    public function selectUnits(){
                $builder = $this->db->table('units');
        $builder->select('unit_id, unit_name');
        return $builder->get()->getResult();
    }

    public function selectDivisionUnit()
    {
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
        $results = array_merge($divisions, $units);

        return $results;
    }
}
