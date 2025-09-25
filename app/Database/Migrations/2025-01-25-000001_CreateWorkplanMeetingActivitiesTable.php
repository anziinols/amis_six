<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkplanMeetingActivitiesTable extends Migration
{
    public function up()
    {
        // Create workplan_meeting_activities table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'workplan_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'proposal_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'meeting_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'agenda' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meeting_date' => [
                'type' => 'DATETIME',
            ],
            'start_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'participants' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'meeting_minutes' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'meeting_images' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'meeting_files' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'gps_coordinates' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'signing_sheet_filepath' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('workplan_id');
        $this->forge->addKey('proposal_id');
        $this->forge->addKey('activity_id');
        $this->forge->addKey('meeting_date');
        $this->forge->addKey('deleted_at');
        $this->forge->createTable('workplan_meeting_activities');
    }

    public function down()
    {
        $this->forge->dropTable('workplan_meeting_activities');
    }
}
