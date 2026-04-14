<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTravelOrderAttachmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'travel_order_attachment_id' => [
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
            'attachment_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
            ],
            'file_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'attachment_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'attachment_type' => [
                'type' => 'ENUM',
                'constraint' => ['Request Memo', 'Request Letter', 'Training Notification','Conference Program', 'Special Order', 'Invitation Letter', 'Meeting Notice', 'Other Document'],
                'null' => true
            ],
        ]);
        $this->forge->addKey('travel_order_attachment_id', true);
            $this->forge->addForeignKey('travel_order_id', 'travel_orders', 'travel_order_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('travel_order_attachments');
    }

    public function down()
    {
        $this->forge->dropTable('travel_order_attachments');
    }
}
