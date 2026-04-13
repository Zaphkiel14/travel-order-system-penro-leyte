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
                'email' => 'admin@example.com',
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
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
