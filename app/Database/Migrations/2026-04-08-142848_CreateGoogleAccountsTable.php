<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGoogleAccountsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'google_acc_id' => [
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
            'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'access_token' => [
                'type' => 'TEXT'
            ],
            'refresh_token' => [
                'type' => 'TEXT'
            ],
            'expires_at' => [
                'type' => 'DATETIME'
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('google_acc_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('google_accounts');
    }

    public function down()
    {
        $this->forge->dropTable('google_accounts');
    }
}
