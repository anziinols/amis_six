<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropFieldsFromWorkplanOthersLinkTable extends Migration
{
    public function up()
    {
        // Drop the specified fields from workplan_others_link table
        $this->forge->dropColumn('workplan_others_link', 'link_type');
        $this->forge->dropColumn('workplan_others_link', 'category');
        $this->forge->dropColumn('workplan_others_link', 'priority_level');
        $this->forge->dropColumn('workplan_others_link', 'duration_months');
    }

    public function down()
    {
        // Add back the fields if rollback is needed
        $this->forge->addColumn('workplan_others_link', [
            'link_type' => [
                'type' => 'ENUM',
                'constraint' => ['recurrent', 'special_project', 'emergency', 'other'],
                'default' => 'other',
                'after' => 'workplan_activity_id',
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'justification',
            ],
            'priority_level' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium',
                'after' => 'category',
            ],
            'duration_months' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'budget_estimate',
            ],
        ]);
    }
}