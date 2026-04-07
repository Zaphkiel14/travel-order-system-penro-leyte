<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttachmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'attachment_id' => [
                'type' => 'MEDIUMINT',
                'constraint' => 8,
                'unsigned' => true,
                'auto_increment' => true
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
        $this->forge->addKey('attachment_id', true);
        $this->forge->createTable('attachments');
    }

    public function down()
    {
        $this->forge->dropTable('attachments');
    }
}
