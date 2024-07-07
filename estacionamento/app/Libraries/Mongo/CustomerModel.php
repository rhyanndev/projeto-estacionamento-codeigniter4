<?php

declare(strict_types=1);

namespace App\Libraries\Mongo;

use App\Libraries\Mongo\Basic\ActionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;

class CustomerModel extends ActionModel
{
    public function __construct()
    {
        parent::__construct(collectionName: 'customers');
    }

    // Buscando todos os clientes, setando o pipeline e retornando todos os documentos
    public function all(): array {
        try {

            $pipeline = $this->setAggregation();

            $cursor = $this->collection->aggregate($pipeline);
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

            $pipeline = $this->setAggregation();
            $pipeline[] = ['$match' => ['_id' => new ObjectId($id)]];

            $document = $this->collection->aggregate($pipeline)->toArray();

            return $document[0] ?? throw new PageNotFoundException("Não localizamos o registro cmo ID: {$id}");
        } catch (\Throwable $th) {

            log_message('error', "Erro ao recuperar o documento no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }



    private function setAggregation(): array {

        $pipeline = [];

        $pipeline[] = [

            '$lookup' => [

                'from'          => 'cars',  //collection
                'localField'    => '_id', //customers._id
                'foreignField'  => 'customer_id', //cars.customer_id
                'as'            => 'cars', // Nome da propriedade no objeto. Ex.: $customer->car 

            ],
        ];
        
        return $pipeline;

    }

}
