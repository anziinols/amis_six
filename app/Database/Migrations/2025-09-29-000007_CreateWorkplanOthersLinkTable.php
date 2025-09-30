<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkplanOthersLinkTable extends Migration
{
    public function up()
    {
        // Create workplan_others_link table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'workplan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'external_plan_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'external_plan_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'link_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'alignment_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'workplan_activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'link_type' => [
                'type' => 'ENUM',
                'constraint' => ['recurrent', 'special_project', 'emergency', 'other'],
                'default' => 'other',
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'justification' => [
                'type' => 'TEXT',
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'priority_level' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium',
            ],
            'expected_outcome' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'target_beneficiaries' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'budget_estimate' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'duration_months' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'completed', 'cancelled'],
                'default' => 'active',
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
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
        $this->forge->addKey('workplan_activity_id');
        $this->forge->addKey('status');
        $this->forge->createTable('workplan_others_link');
    }

    public function down()
    {
        $this->forge->dropTable('workplan_others_link');
    }
}
