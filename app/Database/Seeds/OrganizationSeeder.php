<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'organization_name' => 'PENRO',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('organization')->insertBatch($data);
    }
}
