<?php

namespace App\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetOutputToActivitiesInfrastructure extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities_infrastructure', [
            'target_output' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'infrastructure'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('activities_infrastructure', 'target_output');
    }
}