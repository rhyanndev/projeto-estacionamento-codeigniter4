<?php

namespace App\Models;

use App\Entities\Company;
use CodeIgniter\Model;

class CompanyModel extends Model
{

    // Definindo qual vai ser a conexão com o Postgre no arquivo .env
    protected $DBGroup          = 'company';

    protected $table            = 'information';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Company::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'phone',
        'address',
        'message',
    ];

    //    protected bool $updateOnlyChanged = true;
    //    protected array $casts = [];
    //    protected array $castHandlers = [];

    // Vai buscar na base de dados um registro e se ainda não existir ele 
    // retorna uma nova instância da classe identidade
    public function getCompany(): Company
    {

        return $this->first() ?? new Company();
    }
}
