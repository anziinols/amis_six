<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMiscellaneousTables extends Migration
{
    public function up()
    {
        // Create org_settings table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'settings_code' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'settings_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'settings' => [
                'type' => 'TEXT',
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'deleted_by' => [
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
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('settings_code');
        $this->forge->createTable('org_settings');

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
        $this->forge->addKey('role');
        $this->forge->addKey('dakoii_user_status');
        $this->forge->createTable('dakoii_users');

        // Create adx_country table
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
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('code');
        $this->forge->createTable('adx_country');

        // Create vulnerability table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'province_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'district_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'vulnerability_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'vulnerability_category' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'severity_level' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium',
            ],
            'affected_population' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'geographic_scope' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'seasonal_pattern' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'risk_factors' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'impact_assessment' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'coping_mechanisms' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'intervention_needs' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'priority_ranking' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'data_source' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'assessment_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'next_review_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'vulnerability_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'vulnerability_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'vulnerability_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'vulnerability_status_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('id', true);
        $this->forge->addKey('province_id');
        $this->forge->addKey('district_id');
        $this->forge->addKey('severity_level');
        $this->forge->createTable('vulnerability');

        // Create flyway_schema_history table (for compatibility)
        $this->forge->addField([
            'installed_rank' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'version' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'script' => [
                'type' => 'VARCHAR',
                'constraint' => 1000,
            ],
            'checksum' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'installed_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'installed_on' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'execution_time' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'success' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
        ]);

        $this->forge->addKey('installed_rank', true);
        $this->forge->addKey('success');
        $this->forge->createTable('flyway_schema_history');
    }

    public function down()
    {
        $this->forge->dropTable('flyway_schema_history');
        $this->forge->dropTable('vulnerability');
        $this->forge->dropTable('adx_country');
        $this->forge->dropTable('dakoii_users');
        $this->forge->dropTable('org_settings');
    }
}
