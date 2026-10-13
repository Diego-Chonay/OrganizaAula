<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregaRecuerdoAula extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'remember_token_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => true
            ],
            'remember_expires_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'remember_token_hash',
            'remember_expires_at'
        ]);
    }
}