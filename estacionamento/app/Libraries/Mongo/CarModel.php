<?php

declare(strict_types=1);

namespace App\Libraries\Mongo;

use App\Libraries\Mongo\Basic\ActionModel;
use MongoDB\BSON\ObjectId;

class CarModel extends ActionModel
{
    public function __construct()
    {
        parent::__construct(collectionName: 'cars');
    }

    public function allByCustomerId(string $customerId): array
    {
        try {
           
            $filter = ['customer_id' => new ObjectId($customerId)];
            $cursor = $this->collection->find($filter);
            $documents = $cursor->toArray();

            return $documents;

        } catch (\Throwable $th) {
            log_message('error', "Erro ao recuperar o documento no MongoDB. {$th->getMessage()}");

            exit('Internal Server Error');
        }
    }
}
