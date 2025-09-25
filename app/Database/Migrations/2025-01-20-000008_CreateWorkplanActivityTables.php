<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkplanActivityTables extends Migration
{
    public function up()
    {
        // Create workplan_activities table
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
                'null' => true,
            ],
            'activity_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'activity_type' => [
                'type' => 'ENUM',
                'constraint' => ['training', 'inputs', 'infrastructure', 'output'],
            ],
            'q_one_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'q_two_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'q_three_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'q_four_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'q_one_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'q_two_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'q_three_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'q_four_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'total_budget' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'rated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'rated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'rating' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'reated_remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'supervisor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'status_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status_remarks' => [
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
        $this->forge->addKey('activity_type');
        $this->forge->createTable('workplan_activities');

        // Create workplan_corporate_plan_link table
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
            'workplan_activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'corporate_plan_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'link_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'alignment_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'overarching_objective_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'objective_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'kra_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'strategies_id' => [
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('workplan_activity_id');
        $this->forge->createTable('workplan_corporate_plan_link');

        // Create workplan_mtdp_link table
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
            'workplan_activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'spa_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'sa_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'investment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'kra_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'strategies_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'indicators_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'link_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'alignment_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'indicator_id' => [
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('workplan_activity_id');
        $this->forge->createTable('workplan_mtdp_link');

        // Create workplan_nasp_link table
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
            'workplan_activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'nasp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'apa_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'specific_area_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'objective_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'output_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'link_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'alignment_notes' => [
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
        $this->forge->addKey('workplan_activity_id');
        $this->forge->createTable('workplan_nasp_link');

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
        $this->forge->addKey('workplan_activity_id');
        $this->forge->createTable('workplan_others_link');

        // Create duty_instructions_corporate_plan_link table
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'duty_items_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'corp_strategies_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'created_by' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('duty_items_id');
        $this->forge->addKey('corp_strategies_id');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('duty_instructions_corporate_plan_link');
    }

    public function down()
    {
        $this->forge->dropTable('duty_instructions_corporate_plan_link');
        $this->forge->dropTable('workplan_others_link');
        $this->forge->dropTable('workplan_nasp_link');
        $this->forge->dropTable('workplan_mtdp_link');
        $this->forge->dropTable('workplan_corporate_plan_link');
        $this->forge->dropTable('workplan_activities');
    }
}
