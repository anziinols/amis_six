<?php

namespace App\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetOutputToActivitiesInput extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities_input', [
            'target_output' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'inputs'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activities_input', 'target_output');
    }
}