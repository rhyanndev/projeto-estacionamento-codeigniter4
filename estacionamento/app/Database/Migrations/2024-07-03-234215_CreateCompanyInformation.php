<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompanyInformation extends Migration
{

    // Definindo a conexão de quando for criar a tabela (DBGroup), definida no arquivo .env

    protected $DBGroup = 'company';

    public function up()
    {
        // Definindo as colunas
        $this->forge->addField([

            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ],

            'phone' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
            ],

            'address' => [
                'type'           => 'VARCHAR',
                'constraint'     => 128,
            ],

            'message' => [
                'type'           => 'TEXT',
                'null'           => true,
                'default'        => null,
            ],
        ]);

        // Definindo qual vai ser a primary key
        // 2° argumento informa que o argumento é "true", pois o id é a PK

        $this->forge->addKey('id', true);

        // Instrução de criação da tabela
        $this->forge->createTable('information');

    }

    // Caso deseje-se fazer um rollback
    public function down()
    {
        $this->forge->dropTable('information');
    }
}
