<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('OrganizationSeeder');
        $this->call('DivisionsSeeder');
        $this->call('UnitsSeeder');
    }
}
