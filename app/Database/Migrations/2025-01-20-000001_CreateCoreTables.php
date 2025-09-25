<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreTables extends Migration
{
    public function up()
    {
        // Create users table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'ucode' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone' => [
                'type' => 'TEXT',
            ],
            'fname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'lname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female'],
                'null' => true,
            ],
            'dobirth' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'place_birth' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'employee_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'designation' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'grade' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'report_to_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'is_evaluator' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_supervisor' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_admin' => [
                'type' => 'TINYINT',
                'constraint' => 3,
                'default' => 0,
            ],
            'commodity_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['user', 'guest'],
            ],
            'joined_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'id_photo_filepath' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'user_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'user_status_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'user_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
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
            'activation_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Secure token for account activation',
            ],
            'activation_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Expiration timestamp for activation token',
            ],
            'activated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Timestamp when user completed activation',
            ],
            'is_activated' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Activation status flag (0=pending, 1=activated)',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('ucode');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('branch_id');
        $this->forge->addKey('role');
        $this->forge->addKey('commodity_id');
        $this->forge->addKey('activation_token');
        $this->forge->addKey('is_activated');
        $this->forge->addKey('activation_expires_at');
        $this->forge->createTable('users');

        // Create branches table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'abbrev' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'remarks' => [
                'type' => 'TEXT',
            ],
            'branch_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'branch_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'branch_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'branch_status_remarks' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
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
        $this->forge->addKey('parent_id');
        $this->forge->addKey('branch_status');
        $this->forge->createTable('branches');

        // Create gov_structure table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'json_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'level' => [
                'type' => 'ENUM',
                'constraint' => ['province', 'district', 'llg', 'ward'],
                'comment' => 'province, district, llg, ward',
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'flag_filepath' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'map_center' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'map_zoom' => [
                'type' => 'VARCHAR',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
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
        $this->forge->addKey('parent_id');
        $this->forge->addKey('level');
        $this->forge->addKey('code');
        $this->forge->createTable('gov_structure');
    }

    public function down()
    {
        $this->forge->dropTable('gov_structure');
        $this->forge->dropTable('branches');
        $this->forge->dropTable('users');
    }
}
