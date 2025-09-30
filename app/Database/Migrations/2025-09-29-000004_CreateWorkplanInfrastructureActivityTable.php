<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkplanInfrastructureActivityTable extends Migration
{
    public function up()
    {
        // Create workplan_infrastructure_activity table
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
            'infrastructure_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'infrastructure_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
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
            'estimated_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'actual_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'contractor_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'contract_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'completion_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'progress_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'infrastructure_photos' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'infrastructure_documents' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'completion_certificate' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'handover_document' => [
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
        $this->forge->createTable('workplan_infrastructure_activity');
    }

    public function down()
    {
        $this->forge->dropTable('workplan_infrastructure_activity');
    }
}
