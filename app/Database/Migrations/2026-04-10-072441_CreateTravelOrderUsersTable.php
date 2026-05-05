<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTravelOrderUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'travel_order_user_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'travel_order_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true
            ],
            'user_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'null' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'salary_grade' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
        ]);
        $this->forge->addKey('travel_order_user_id', true);
        $this->forge->addForeignKey('travel_order_id', 'travel_orders', 'travel_order_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('travel_order_users');
    }

    public function down() {}
}
