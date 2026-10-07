<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ConstruyeUsuariosAula extends Migration
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

    'name' => [
        'type'       => 'VARCHAR',
        'constraint' => 100,
    ],

    'email' => [
        'type'       => 'VARCHAR',
        'constraint' => 150,
    ],

    'password' => [
        'type'       => 'VARCHAR',
        'constraint' => 255,
    ],

     'academic_role' => [
	'type'	    => 'VARCHAR',
	'constraint'  => 20,
   ],

    'active' => [
        'type'       => 'TINYINT',
        'constraint' => 1,
        'default'    => 0,
    ],

    'administrator' => [
        'type'       => 'TINYINT',
        'constraint' => 1,
        'default'    => 0,
    ],

    'profile_image' => [
        'type'       => 'VARCHAR',
        'constraint' => 255,
        'null'       => true,
    ],

    'verification_key' => [
        'type'       => 'VARCHAR',
        'constraint' => 255,
        'null'       => true,
    ],

    'recovery_key' => [
        'type'       => 'VARCHAR',
        'constraint' => 255,
        'null'       => true,
    ],

    'recovery_expires_at' => [
        'type' => 'DATETIME',
        'null' => true,
    ],

    'created_at' => [
        'type' => 'DATETIME',
    ],

    'updated_at' => [
        'type' => 'DATETIME',
    ],
]);

$this->forge->addKey('id', true);
$this->forge->addUniqueKey('email');

$this->forge->createTable('users');

    }
    public function down()
    {
        $this->forge->dropTable('users');
    }
}
