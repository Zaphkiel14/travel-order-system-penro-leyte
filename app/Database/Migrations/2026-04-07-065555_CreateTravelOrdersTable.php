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
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Pending Supervisor', 'For Division Approval', 'For PENRO Approval', 'Approved', 'Rejected'],
                'default' => 'Pending Supervisor'
            ],
            'approved_by_supervisor' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'supervisor_remarks' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'approved_by_division_head' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'division_head_remarks' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'approved_by_organization_head' => [
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
        $this->forge->createTable('travel_orders');
    }

    public function down()
    {
        $this->forge->dropTable('travel_orders');
    }
}
