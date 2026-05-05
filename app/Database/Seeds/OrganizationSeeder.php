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
                'organization_head_id' => 1, // Assuming user_id 1 is the admin
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('organization')->insertBatch($data);
    }
}
