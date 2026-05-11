<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DivisionsSeeder extends Seeder
{
    public function run()
    {
        $organizationId = $this->db->table('organization')
            ->where('organization_name', 'PENRO')
            ->get()
            ->getRow()
            ->organization_id;
        $data = [
            [
                'organization_id' => $organizationId,
                'division_name' => 'MSD',
                'division_head_id' => 4,
                'division_head_position' => 'Division Head',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'organization_id' => $organizationId,
                'division_name' => 'TSD',
                'division_head_id' => 5,
                'division_head_position' => 'Division Head',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('divisions')->insertBatch($data);
    }
}
