<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRemainingTables extends Migration
{
    public function up()
    {
        // Create commodity_prices table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'commodity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'date_from' => [
                'type' => 'DATE',
            ],
            'date_to' => [
                'type' => 'DATE',
            ],
            'item' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'unit_of_measurement' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'currency' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 'PGK',
            ],
            'market_location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'price_type' => [
                'type' => 'ENUM',
                'constraint' => ['wholesale', 'retail', 'farm_gate', 'export'],
                'default' => 'retail',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'is_deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('commodity_id');
        $this->forge->addKey('date_from');
        $this->forge->createTable('commodity_prices');

        // Create audit_trail table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'table_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'record_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'old_values' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'new_values' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('table_name');
        $this->forge->addKey('created_at');
        $this->forge->createTable('audit_trail');

        // Create migrations table (for CodeIgniter compatibility)
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'version' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'class' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'group' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'namespace' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'time' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'batch' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('migrations');

        // Create workplan_report table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'workplan_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'report_period' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'report_type' => [
                'type' => 'ENUM',
                'constraint' => ['quarterly', 'annual', 'mid_year', 'special'],
                'default' => 'quarterly',
            ],
            'quarter' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
            ],
            'year' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'report_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'executive_summary' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'achievements' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'challenges' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'recommendations' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'next_quarter_plans' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'budget_utilization' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'performance_rating' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'null' => true,
            ],
            'attachments' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted', 'reviewed', 'approved', 'published'],
                'default' => 'draft',
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'submitted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'reviewed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'approved_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'remarks' => [
                'type' => 'TEXT',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('workplan_id');
        $this->forge->addKey('branch_id');
        $this->forge->addKey('year');
        $this->forge->addKey('quarter');
        $this->forge->createTable('workplan_report');

        // Create workplan_report_activities table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'workplan_report_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'workplan_activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'planned_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'actual_achievement' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'variance' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'variance_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'implementation_status' => [
                'type' => 'ENUM',
                'constraint' => ['not_started', 'in_progress', 'completed', 'delayed', 'cancelled'],
                'default' => 'not_started',
            ],
            'challenges_faced' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'lessons_learned' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'budget_allocated' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'budget_utilized' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'budget_variance' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'remarks' => [
                'type' => 'TEXT',
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('workplan_report_id');
        $this->forge->addKey('workplan_activity_id');
        $this->forge->createTable('workplan_report_activities');
    }

    public function down()
    {
        $this->forge->dropTable('workplan_report_activities');
        $this->forge->dropTable('workplan_report');
        $this->forge->dropTable('migrations');
        $this->forge->dropTable('audit_trail');
        $this->forge->dropTable('commodity_prices');
    }
}
