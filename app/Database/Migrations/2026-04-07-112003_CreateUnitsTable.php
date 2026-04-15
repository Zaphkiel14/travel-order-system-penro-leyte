<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'unit_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'division_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true
            ],
            'unit_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'unit_supervisor_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'null' => true
            ],
            'unit_supervisor_position' => [
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
        $this->forge->addKey('unit_id', true);
        $this->forge->addForeignKey('unit_supervisor_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('division_id', 'divisions', 'division_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('units');

    }

    public function down()
    {
        $this->forge->dropTable('units');
    }
}
