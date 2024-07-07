<?php

declare(strict_types=1);

namespace App\Libraries\Mongo;

use App\Libraries\Mongo\Basic\ActionModel;

class CategoryModel extends ActionModel
{
    public function __construct()
    {
        parent::__construct(collectionName: 'categories');
    }

}
