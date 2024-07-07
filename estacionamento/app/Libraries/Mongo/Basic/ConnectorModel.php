<?php

declare(strict_types=1);

namespace App\Libraries\Mongo\Basic;

use Exception;
use MongoDB\Client;

abstract class ConnectorModel {
    
    protected $collection;

    public function __construct(string $collectionName)
    {
        $uri = env('MONGO_URI');
        $database = env('MONGO_DATABASE');

        try {
            
            if(empty($uri) || empty($database)){

                throw new Exception('Por favor defina os dados de acesso no arquivo .env');
            }

            $client = new Client($uri);

            //Chamando a instância da classe, conectando com o banco de dados e acessando a collection name
            $this->collection = $client->$database->$collectionName; 

        } catch (\Throwable $th) {
            log_message('error', "Erro ao se conectar ao mongo db: {$th->getMessage()}");

            exit('Internal Server Error');

        }

    }

}