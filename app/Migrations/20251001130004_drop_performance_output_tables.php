<?php

namespace App\Migrations;

use CodeIgniter\Database\Migration;

class DropPerformanceOutputTables extends Migration
{
    public function up()
    {
        // Drop the junction tables first (they have foreign key constraints)
        $this->forge->dropTable('output_duty_instruction', true);
        $this->forge->dropTable('output_workplan_activities', true);
        
        // Drop the main table
        $this->forge->dropTable('performance_outputs', true);
    }

    public function down()
    {
        // This migration is not reversible as we don't have the original table structures
        // If you need to restore these tables, you'll need to create a new migration
        // with the proper table structures
    }
}