<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMtdpDetailTables extends Migration
{
    public function up()
    {
        // Create plans_mtdp_spa table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'remarks' => [
                'type' => 'TEXT',
            ],
            'spa_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'spa_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'spa_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'spa_status_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('mtdp_id');
        $this->forge->createTable('plans_mtdp_spa');

        // Create plans_mtdp_dip table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'spa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'dip_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'dip_remarks' => [
                'type' => 'TEXT',
            ],
            'investments' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kras' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'strategies' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'indicators' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'dip_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'dip_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'dip_status_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('mtdp_id');
        $this->forge->addKey('spa_id');
        $this->forge->createTable('plans_mtdp_dip');

        // Create plans_mtdp_specific_area table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'spa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sa_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'sa_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'sa_remarks' => [
                'type' => 'TEXT',
            ],
            'sa_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'sa_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sa_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'sa_status_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('mtdp_id');
        $this->forge->addKey('spa_id');
        $this->forge->createTable('plans_mtdp_specific_area');

        // Create plans_mtdp_investments table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'spa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_link_dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'investment' => [
                'type' => 'TEXT',
            ],
            'year_one' => [
                'type' => 'DECIMAL',
                'constraint' => '18,2',
                'default' => '0.00',
            ],
            'year_two' => [
                'type' => 'DECIMAL',
                'constraint' => '18,2',
                'default' => '0.00',
            ],
            'year_three' => [
                'type' => 'DECIMAL',
                'constraint' => '18,2',
                'default' => '0.00',
            ],
            'year_four' => [
                'type' => 'DECIMAL',
                'constraint' => '18,2',
                'default' => '0.00',
            ],
            'year_five' => [
                'type' => 'DECIMAL',
                'constraint' => '18,2',
                'default' => '0.00',
            ],
            'funding_sources' => [
                'type' => 'TEXT',
            ],
            'investment_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'investment_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'investment_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'investment_status_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('mtdp_id');
        $this->forge->createTable('plans_mtdp_investments');

        // Create plans_mtdp_kra table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'spa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'investment_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'kpi' => [
                'type' => 'TEXT',
            ],
            'year_one' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_two' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_three' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_four' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_five' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'responsible_agencies' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'kra_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'kra_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'kra_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'kra_status_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('mtdp_id');
        $this->forge->createTable('plans_mtdp_kra');

        // Create plans_mtdp_strategies table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'spa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'investment_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'kra_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'strategy' => [
                'type' => 'TEXT',
            ],
            'policy_reference' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'strategies_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'strategies_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'strategies_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'strategies_status_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('mtdp_id');
        $this->forge->createTable('plans_mtdp_strategies');

        // Create plans_mtdp_indicators table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'mtdp_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'spa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dip_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sa_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'investment_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'kra_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'strategies_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'indicator' => [
                'type' => 'TEXT',
            ],
            'source' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'baseline' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_one' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_two' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_three' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_four' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'year_five' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'indicators_status' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'default' => 1,
            ],
            'indicators_status_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'indicators_status_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'indicators_status_remarks' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('mtdp_id');
        $this->forge->createTable('plans_mtdp_indicators');
    }

    public function down()
    {
        $this->forge->dropTable('plans_mtdp_indicators');
        $this->forge->dropTable('plans_mtdp_strategies');
        $this->forge->dropTable('plans_mtdp_kra');
        $this->forge->dropTable('plans_mtdp_investments');
        $this->forge->dropTable('plans_mtdp_specific_area');
        $this->forge->dropTable('plans_mtdp_dip');
        $this->forge->dropTable('plans_mtdp_spa');
    }
}
