<?php namespace App\Database\Migrations;

class UsersTable extends \CodeIgniter\Database\Migration
{

    public function up()
    {
        $this->forge->addField(
            [
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '250',
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'unique'     => true,
                    'constraint' => '250',
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '250',
                ],
                'created_at datetime default current_timestamp',
                'updated_at datetime default current_timestamp on update current_timestamp',
            ]
        );
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}