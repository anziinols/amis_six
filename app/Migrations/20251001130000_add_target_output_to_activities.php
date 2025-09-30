<?php

namespace App\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetOutputToActivities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities', [
            'target_output' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'activity_description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activities', 'target_output');
    }
}