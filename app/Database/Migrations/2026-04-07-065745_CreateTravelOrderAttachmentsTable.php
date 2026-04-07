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
                'unsigned' => true
            ]
        ]);
        $this->forge->addKey('travel_order_attachment_id', true);
            $this->forge->addForeignKey('travel_order_id', 'travel_orders', 'travel_order_id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('attachment_id', 'attachments', 'attachment_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('travel_order_attachments');
    }

    public function down()
    {
        $this->forge->dropTable('travel_order_attachments');
    }
}
