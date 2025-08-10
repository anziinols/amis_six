<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommodityPricesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'commodity_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'price_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'market_type' => [
                'type'       => 'ENUM',
                'constraint' => ['local', 'export', 'wholesale', 'retail'],
                'default'    => 'local',
            ],
            'price_per_unit' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
            ],
            'unit_of_measurement' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'currency' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => CURRENCY_CODE,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'source' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Source of price data (e.g., market survey, official report)',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ],
            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'is_deleted' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('commodity_id');
        $this->forge->addKey('price_date');
        $this->forge->addKey('market_type');
        $this->forge->addKey(['commodity_id', 'price_date', 'market_type']);
        $this->forge->addForeignKey('commodity_id', 'commodities', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('commodity_prices');
    }

    public function down()
    {
        $this->forge->dropTable('commodity_prices');
    }
}
