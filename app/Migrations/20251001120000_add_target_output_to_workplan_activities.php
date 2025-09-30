<?php

namespace App\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetOutputToWorkplanActivities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('workplan_activities', [
            'target_output' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('workplan_activities', 'target_output');
    }
}