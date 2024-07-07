<?php

declare(strict_types=1);

namespace App\Libraries\Mongo;

use App\Entities\Ticket;
use App\Libraries\Mongo\Basic\ActionModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;
use PhpParser\Node\Expr\Cast\Object_;

use function PHPUnit\Framework\throwException;

class TicketModel extends ActionModel
{
    public function __construct()
    {
        parent::__construct(collectionName: 'tickets');
    }

    public function getAll(array $filter = [], bool $transformToTicket = false): array
    {
        try {

            $pipeline = $this->setAggregation();

            // Temos algum valor definido no filter?
            if(! empty($filter)){

                // Sim, devemos adicioná-lo no pipeline
                $pipeline[] = ['$match' => $filter];
            }

            //buscamos os documentos
            $documents = $this->collection->aggregate($pipeline)->toArray();

            if(empty($documents)){

                return [];
            }

            if(! $transformToTicket){

                // documentos da classe BsonDocument
                return $documents;    
            }
            
            //Transformamos os documentos em entidades de Ticket
            return $this->transformToTicket($documents);

        } catch (\Throwable $th) {

            log_message('error', "Erro ao recuperar os tickets no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }

    public function getOrFail(string $id): Ticket
    {
        $pipeline = $this->setAggregation();
        $pipeline[] = ['$match' => ['_id' =>  new ObjectId($id)]];

        $result = $this->collection->aggregate($pipeline)->toArray();

        $document = $result[0] ?? null;

        return $document !== null ? new Ticket((array) $document) : 
        throw new PageNotFoundException("Não localizamos o ticket ID: {$id}");

    }

    private function transformToTicket(&$documents): array {

        $tickets = [];

        foreach($documents as $document){

            $tickets[] = new Ticket((array)$document);

        }

        return $tickets;
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


        //--------- Agregação de carro --------- //

        $pipeline[] = [

            '$lookup' => [

                'from'          => 'cars',  //collection
                'localField'    => 'car_id',
                'foreignField'  => '_id',
                'as'            => 'car_array',

            ],
        ];

        $pipeline[] = [
            '$addFields' => [

                'car' => [

                    '$arrayElemAt' => [

                        '$car_array', 0
                    ] // pegamos a primeira posição, dessa forma temos um objeto
                ],
            ],
        ];

        $pipeline[] = [
            '$unset' => 'car_array', // não precisamos mais
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

                // atributos da collection tickets

                '_id'               => '$_id',
                'payment_method'    => ['$first' => '$payment_method'],
                'status'            => ['$first' => '$status'],
                'spot'              => ['$first' => '$spot'],
                'vehicle'           => ['$first' => '$vehicle'],
                'plate'             => ['$first' => '$plate'],
                'observations'      => ['$first' => '$observations'],
                'category_value'    => ['$first' => '$category_value'],
                'amount_park'       => ['$first' => '$amount_park'],
                'amount_paid'       => ['$first' => '$amount_paid'],
                'elapsed_time'      => ['$first' => '$elapsed_time'],
                'choice'            => ['$first' => '$choice'],
                'created_at'        => ['$first' => '$created_at'],
                'updated_at'        => ['$first' => '$updated_at'],


                // Atributos de outras collections

                'customer'        => ['$first' => '$customer'],
                'car'             => ['$first' => '$car'],
                'category'        => ['$first' => '$category'],

            ],
        ];

        // Finalmente retornamos o $pipeline

        return $pipeline;
    }
}
