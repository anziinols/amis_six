<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdxCountryTable extends Migration
{
    public function up()
    {
        // Create adx_country table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('code', false, false, 'idx_code');
        $this->forge->createTable('adx_country');
    }

    public function down()
    {
        $this->forge->dropTable('adx_country');
    }
}
