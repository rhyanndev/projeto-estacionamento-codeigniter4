<?php

declare(strict_types=1);

namespace App\Libraries\Mongo;

use App\Libraries\Mongo\Basic\ActionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;

class MonthlyFeeModel extends ActionModel
{
    public function __construct()
    {
        parent::__construct(collectionName: 'monthly_fees');
    }

    public function all(): array
    {
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

            $result = $this->collection->aggregate($pipeline)->toArray();

            return $result[0] ?? throw new PageNotFoundException("Não localizamos o registro cmo ID: {$id}");
        } catch (\Throwable $th) {

            log_message('error', "Erro ao recuperar o documento no MongoDB: {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }

    private function setAggregation(): array
    {   

        $pipeline = [];

        //--------- Agregação do customer --------- //

        $pipeline[] = [

            '$lookup' => [

                'from'          => 'customers',  //collection
                'localField'    => 'customer_id',
                'foreignField'  => '_id',
                'as'            => 'customer_array',

            ],
        ];

        $pipeline[] = [
            '$addFields' => [

                'customer' => [

                    '$arrayElemAt' => [

                        '$customer_array', 0
                    ] // pegamos a primeira posição, dessa forma temos um objeto
                ],
            ],
        ];

        $pipeline[] = [
            '$unset' => 'customer_array', // não precisamos mais
        ];


        //--------- Agregação da categoria --------- //

        $pipeline[] = [

            '$lookup' => [

                'from'          => 'categories',  //collection
                'localField'    => 'category_id',
                'foreignField'  => '_id',
                'as'            => 'category_array',

            ],
        ];

        $pipeline[] = [
            '$addFields' => [

                'category' => [

                    '$arrayElemAt' => [

                        '$category_array', 0
                    ] // pegamos a primeira posição, dessa forma temos um objeto
                ],
            ],
        ];

        $pipeline[] = [
            '$unset' => 'category_array', // não precisamos mais
        ];

        //--------- Agrupar os dados --------- //

        $pipeline[] = [

            '$group' => [

                // atributos da collection monthly_fees
                '_id'               => '$_id',
                'due_date'          => ['$first' => '$due_date'],
                'status'            => ['$first' => '$status'],

                // Atributos de outras collections
                'customer'        => ['$first' => '$customer'],
                'category'        => ['$first' => '$category'],
            ],
        ];

        // Finalmente retornamos o $pipeline

        return $pipeline;
    }

}
