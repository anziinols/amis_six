<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetOutputToWorkplanActivities extends Migration
{
    public function up()
    {
        // Add target_output column if it doesn't exist
        if (!$this->db->fieldExists('target_output', 'workplan_activities')) {
            $fields = [
                'target_output' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => true,
                    'after' => 'description'
                ]
            ];
            
            $this->forge->addColumn('workplan_activities', $fields);
        }
    }

    public function down()
    {
        // Remove target_output column if it exists
        if ($this->db->fieldExists('target_output', 'workplan_activities')) {
            $this->forge->dropColumn('workplan_activities', 'target_output');
        }
    }
}
