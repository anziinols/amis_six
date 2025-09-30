<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDakoiiUsersTable extends Migration
{
    public function up()
    {
        // Create dakoii_users table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'dakoii_user_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'dakoii_user_status_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'dakoii_user_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'dakoii_user_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addKey('role', false, false, 'idx_role');
        $this->forge->addKey('dakoii_user_status', false, false, 'idx_dakoii_user_status');
        $this->forge->createTable('dakoii_users');
    }

    public function down()
    {
        $this->forge->dropTable('dakoii_users');
    }
}
