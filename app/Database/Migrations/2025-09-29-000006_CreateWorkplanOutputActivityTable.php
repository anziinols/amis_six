<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkplanOutputActivityTable extends Migration
{
    public function up()
    {
        // Create workplan_output_activity table
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
            'workplan_activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'output_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'output_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'quantity_planned' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'quantity_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'unit_of_measurement' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'quality_standards' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'beneficiaries_target' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'beneficiaries_actual' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'beneficiary_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'gps_coordinates' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'implementation_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'completion_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'output_value' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'funding_source' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'partnership_details' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'challenges_faced' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'lessons_learned' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'success_indicators' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'output_photos' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'output_documents' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'monitoring_report' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'planned',
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
        $this->forge->addKey('workplan_id');
        $this->forge->addKey('workplan_activity_id');
        $this->forge->addKey('status');
        $this->forge->createTable('workplan_output_activity');
    }

    public function down()
    {
        $this->forge->dropTable('workplan_output_activity');
    }
}
