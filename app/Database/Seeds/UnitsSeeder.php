<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UnitsSeeder extends Seeder
{
    public function run()
    {

        $MDS = $this->db->table('divisions')
            ->where('division_name', 'MSD')
            ->get()
            ->getRow()
            ->division_id;
        $TDS = $this->db->table('divisions')
            ->where('division_name', 'TSD')
            ->get()
            ->getRow()
            ->division_id;
        $data = [
            [
                'division_id' => $MDS,
                'unit_name' => 'Accounting',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'Budget',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'Cashier',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'HRD',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'GSS',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'Procurement & Supply',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'Planning & Monitoring',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'Records',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $MDS,
                'unit_name' => 'Receiving',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $TDS,
                'unit_name' => 'CDS',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $TDS,
                'unit_name' => 'MES',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $TDS,
                'unit_name' => 'NGP',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'division_id' => $TDS,
                'unit_name' => 'RPS',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('units')->insertBatch($data);
    }
}
