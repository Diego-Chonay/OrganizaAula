<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ConstruyeTareasAula extends Migration
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

    'creator_id' => [
        'type'       => 'INT',
        'constraint' => 11,
        'unsigned'   => true,
    ],

    'description' => [
        'type'       => 'VARCHAR',
        'constraint' => 255,
    ],

    'created_at' => [
        'type' => 'DATETIME',
    ],

    'updated_at' => [
        'type' => 'DATETIME',
    ],
]);

$this->forge->addKey('id', true);

$this->forge->addForeignKey(
    'creator_id',
    'users',
    'id',
    'CASCADE',
    'RESTRICT'
);

$this->forge->createTable('tasks');
    }

    public function down()
    {
         $this->forge->dropTable('tasks');
    }
}
