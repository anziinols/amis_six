<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWorkplanActivitiesTable extends Migration
{
    public function up()
    {
        // Remove activity_type column
        if ($this->db->fieldExists('activity_type', 'workplan_activities')) {
            $this->forge->dropColumn('workplan_activities', 'activity_type');
        }

        // Remove quarter target columns
        $quarterTargetColumns = ['q_one_target', 'q_two_target', 'q_three_target', 'q_four_target'];
        foreach ($quarterTargetColumns as $column) {
            if ($this->db->fieldExists($column, 'workplan_activities')) {
                $this->forge->dropColumn('workplan_activities', $column);
            }
        }

        // Remove quarter achieved columns
        $quarterAchievedColumns = ['q_one_achieved', 'q_two_achieved', 'q_three_achieved', 'q_four_achieved'];
        foreach ($quarterAchievedColumns as $column) {
            if ($this->db->fieldExists($column, 'workplan_activities')) {
                $this->forge->dropColumn('workplan_activities', $column);
            }
        }

        // Add target_output column after description
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

    public function down()
    {
        // Remove target_output column
        if ($this->db->fieldExists('target_output', 'workplan_activities')) {
            $this->forge->dropColumn('workplan_activities', 'target_output');
        }

        // Add back activity_type column
        $fields = [
            'activity_type' => [
                'type' => 'ENUM',
                'constraint' => ['training', 'inputs', 'infrastructure', 'output'],
                'null' => false,
                'after' => 'description'
            ]
        ];
        $this->forge->addColumn('workplan_activities', $fields);

        // Add back quarter target columns
        $quarterTargetFields = [
            'q_one_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'activity_type'
            ],
            'q_two_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'q_one_target'
            ],
            'q_three_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'q_two_target'
            ],
            'q_four_target' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'q_three_target'
            ]
        ];

        $this->forge->addColumn('workplan_activities', $quarterTargetFields);

        // Add back quarter achieved columns
        $quarterAchievedFields = [
            'q_one_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'q_four_target'
            ],
            'q_two_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'q_one_achieved'
            ],
            'q_three_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'q_two_achieved'
            ],
            'q_four_achieved' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
                'after' => 'q_three_achieved'
            ]
        ];

        $this->forge->addColumn('workplan_activities', $quarterAchievedFields);
    }
}
