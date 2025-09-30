<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkplanInputActivityTable extends Migration
{
    public function up()
    {
        // Create workplan_input_activity table
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
            'input_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'input_type' => [
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
            'quantity_delivered' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'unit_of_measurement' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'unit_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'total_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'supplier_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'supplier_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'procurement_method' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'purchase_order_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'delivery_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'delivery_location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'gps_coordinates' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'quality_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'input_photos' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'delivery_note' => [
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
        $this->forge->createTable('workplan_input_activity');
    }

    public function down()
    {
        $this->forge->dropTable('workplan_input_activity');
    }
}
