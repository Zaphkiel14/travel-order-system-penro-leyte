<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'vincenteleazar.uykieng@evsu.edu.ph',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'position' => 'Administrator',
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'user',
                'last_name' => 'user',
                'email' => 'user@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'position' => 'User',
                'role' => 'employee',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Penro',
                'last_name' => 'Penro',
                'email' => 'penro@example.com',
                'password' => password_hash('penro123', PASSWORD_DEFAULT),
                'position' => 'User',
                'role' => 'penro',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Division',
                'last_name' => 'Head',
                'email' => 'divisionhead@example.com',
                'password' => password_hash('divisionhead123', PASSWORD_DEFAULT),
                'position' => 'Division Head',
                'role' => 'division',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Supervisor',
                'last_name' => 'Supervisor',
                'email' => 'supervisor@example.com',
                'password' => password_hash('supervisor123', PASSWORD_DEFAULT),
                'position' => 'Supervisor',
                'role' => 'unit',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Records',
                'last_name' => 'Records',
                'email' => 'records@example.com',
                'password' => password_hash('records123', PASSWORD_DEFAULT),
                'position' => 'Records',
                'role' => 'records',
                'created_at' => date('Y-m-d H:i:s'),
            ]


        ];

        $this->db->table('users')->insertBatch($data);
    }
}
