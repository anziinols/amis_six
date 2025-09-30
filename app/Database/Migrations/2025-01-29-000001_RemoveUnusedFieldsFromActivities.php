<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveUnusedFieldsFromActivities extends Migration
{
    public function up()
    {
        // Remove workplan_period_id and performance_output_id columns from activities table
        $this->forge->dropColumn('activities', ['workplan_period_id', 'performance_output_id']);
        
        // Also remove any indexes that might reference these columns
        try {
            $this->forge->dropKey('activities', 'workplan_period_id');
        } catch (\Exception $e) {
            // Index might not exist, continue
        }
        
        try {
            $this->forge->dropKey('activities', 'performance_output_id');
        } catch (\Exception $e) {
            // Index might not exist, continue
        }
    }

    public function down()
    {
        // Add the columns back if we need to rollback
        $this->forge->addColumn('activities', [
            'workplan_period_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true, // Make nullable for rollback safety
                'after' => 'id'
            ],
            'performance_output_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true, // Make nullable for rollback safety
                'after' => 'workplan_period_id'
            ]
        ]);
        
        // Add back the indexes
        $this->forge->addKey('activities', 'workplan_period_id');
        $this->forge->addKey('activities', 'performance_output_id');
    }
}
