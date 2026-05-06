<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTravelOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'travel_order_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true
            ],
            'travel_order_number' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true
            ],
            'departure_date' => [
                'type' => 'DATE'
            ],
            'arrival_date' => [
                'type' => 'DATE'
            ],
            'destination' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'purpose_of_travel' => [
                'type' => 'TEXT'
            ],
            'current_status' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => 'true'
            ],
            'current_level' => [
                'type' => 'ENUM',
                'constraint' => [
                                    'unit',
                                    'division',
                                    'organization',
                                    'records'
                ]
            ],
            'unit_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'null' => true
            ],
            'unit_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending'
            ],
            'assigned_to_unit_head' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'supervisor_remarks' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'division_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'null' => true
            ],
            'division_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending'
            ],
            'assigned_to_division_head' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'division_head_remarks' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'organization_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'null' => true
            ],
            'organization_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending'
            ],
            'assigned_to_organization_head' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],

            'organization_head_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('travel_order_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'unit_id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('division_id', 'divisions', 'division_id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('organization_id', 'organization', 'organization_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('travel_orders');
    }

    public function down()
    {
        $this->forge->dropTable('travel_orders');
    }
}
