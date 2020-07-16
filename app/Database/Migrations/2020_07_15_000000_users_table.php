<?php namespace App\Database\Migrations;

class UsersTable extends \CodeIgniter\Database\Migration {

    public function up()
    {
        $this->forge->addField([
                                   'id'          => [
                                       'type'           => 'INT',
                                       'constraint'     => 11,
                                       'unsigned'       => TRUE,
                                       'auto_increment' => TRUE
                                   ],
                                   'name'       => [
                                       'type'           => 'VARCHAR',
                                       'constraint'     => '250',
                                   ],
                                   'email'       => [
                                       'type'           => 'VARCHAR',
                                       'unique'         => TRUE,
                                       'constraint'     => '250',
                                   ],
                                   'password'       => [
                                       'type'           => 'VARCHAR',
                                       'constraint'     => '250',
                                   ],
                                   'created_at datetime default current_timestamp',
                                   'updated_at datetime default current_timestamp on update current_timestamp',
                               ]);
        $this->forge->addKey('id', TRUE);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}