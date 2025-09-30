<?php

namespace App\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetOutputToActivitiesTraining extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities_training', [
            'target_output' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'trainees'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activities_training', 'target_output');
    }
}