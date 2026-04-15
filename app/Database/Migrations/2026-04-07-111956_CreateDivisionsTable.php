<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDivisionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'division_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'organization_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true
            ],
            'division_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'division_head_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'null' => true
            ],
            'division_head_position' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('division_id', true);
        $this->forge->addForeignKey('division_head_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('organization_id', 'organization', 'organization_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('divisions');

    }

    public function down()
    {
        $this->forge->dropTable('divisions');
    }
}
