<?php

declare(strict_types=1);

namespace App\Libraries\Mongo\Basic;

use CodeIgniter\Exceptions\PageNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;
use PhpParser\Node\Expr\Cast\Object_;

//Criando o modelo para que o registro seja criado no MongoDB

abstract class ActionModel extends ConnectorModel
{
    //Para fazer ações no MongoDB, necessita de um conector (ConnectorModel)

    public function __construct(string $collectionName)
    {
        parent::__construct(collectionName: $collectionName);
    }

    // Método que irá buscar os registros
    public function all(): array
    {
        try {

            $cursor = $this->collection->find([]);
            $documents = $cursor->toArray();

            return $documents;
        } catch (\Throwable $th) {

            log_message('error', "Erro ao recuperar os registros no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }

    public function findOrFail(string $id): BSONDocument
    {
        try {

            $document = $this->collection->findOne(['_id' => new ObjectId($id)]);

            return $document ?? throw new PageNotFoundException("Não localizamos o registro cmo ID: {$id}");
        } catch (\Throwable $th) {

            log_message('error', "Erro ao recuperar o documento no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }


    //Método que vai criar o registro lá na base de dados
    public function create(array $data): bool
    {
        //Se o registro for criado, retorna um true, caso não false
        try {

            //Dados já foram validados. Escapando todos os dados
            $data = esc($data);

            $result = $this->collection->insertOne($data);

            //Vai verificar se o registro foi inserido, caso for igual 1 = true
            return $result->getInsertedCount() === 1;
        } catch (\Throwable $th) {

            log_message('error', "Erro ao inserir o registro no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }

    public function update(string $id, array $data): bool
    {
        try {

            $data = esc($data);

            $result = $this->collection->updateOne(['_id' => new ObjectId($id)], ['$set' => $data]);

            return $result->getModifiedCount() ? true : false;
        } catch (\Throwable $th) {

            log_message('error', "Erro ao atualizar o registro no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }

    public function delete(string $id): bool
    {
        try {

            $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);

            return $result->getDeletedCount() === 1;
        } catch (\Throwable $th) {

            log_message('error', "Erro ao excluir o documento no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }
}
