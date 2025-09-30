<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateOutputDutyInstructionTable extends Migration
{
    public function up()
    {
        // Check if the table exists and has the old column name
        if ($this->db->tableExists('output_duty_instruction')) {
            // Check if the old column exists
            if ($this->db->fieldExists('duty_instruction_id', 'output_duty_instruction')) {
                // Rename the column from duty_instruction_id to duty_instruction_item_id
                $this->forge->modifyColumn('output_duty_instruction', [
                    'duty_instruction_id' => [
                        'name' => 'duty_instruction_item_id',
                        'type' => 'BIGINT',
                        'constraint' => 20,
                        'unsigned' => true,
                    ]
                ]);
            }
        }
    }

    public function down()
    {
        // Revert the column name change
        if ($this->db->tableExists('output_duty_instruction')) {
            if ($this->db->fieldExists('duty_instruction_item_id', 'output_duty_instruction')) {
                $this->forge->modifyColumn('output_duty_instruction', [
                    'duty_instruction_item_id' => [
                        'name' => 'duty_instruction_id',
                        'type' => 'BIGINT',
                        'constraint' => 20,
                        'unsigned' => true,
                    ]
                ]);
            }
        }
    }
}
