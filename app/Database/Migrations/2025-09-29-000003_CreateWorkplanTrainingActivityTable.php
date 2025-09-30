<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkplanTrainingActivityTable extends Migration
{
    public function up()
    {
        // Create workplan_training_activity table
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
            'training_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'training_objectives' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'training_topics' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'trainers' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'trainees_target' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'trainees_actual' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'training_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'duration_hours' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'venue' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
            'training_materials' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'training_photos' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'attendance_sheet' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'evaluation_results' => [
                'type' => 'TEXT',
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
        $this->forge->createTable('workplan_training_activity');
    }

    public function down()
    {
        $this->forge->dropTable('workplan_training_activity');
    }
}
