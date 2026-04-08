<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrganizationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'organization_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'organization_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255     
            ],
            'organization_user_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ], 
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]   
        ]);
        $this->forge->addKey('organization_id', true);
        $this->forge->addForeignKey('organization_user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('organization');
    }

    public function down()
    {
        $this->forge->dropTable('organization');
    }
}
