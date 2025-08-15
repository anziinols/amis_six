<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivitiesTables extends Migration
{
    public function up()
    {
        // Create activities table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'workplan_period_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'performance_output_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'supervisor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'action_officer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'activity_title' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'activity_description' => [
                'type' => 'TEXT',
            ],
            'province_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'district_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'date_start' => [
                'type' => 'DATE',
            ],
            'date_end' => [
                'type' => 'DATE',
            ],
            'total_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['documents', 'trainings', 'meetings', 'agreements', 'inputs', 'infrastructures', 'outputs'],
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'active', 'submitted', 'approved', 'rated'],
                'default' => 'pending',
            ],
            'status_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
            'rating_score' => [
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'null' => true,
            ],
            'rated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'rated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'rate_remarks' => [
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
                'unsigned' => true,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('workplan_period_id');
        $this->forge->addKey('performance_output_id');
        $this->forge->addKey('type');
        $this->forge->addKey('status');
        $this->forge->createTable('activities');

        // Create activities_training table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'trainers' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'topics' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'trainees' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'training_images' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'training_files' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'gps_coordinates' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'signing_sheet_filepath' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('activity_id');
        $this->forge->createTable('activities_training');
    }

    public function down()
    {
        $this->forge->dropTable('activities_training');
        $this->forge->dropTable('activities');
    }
}
