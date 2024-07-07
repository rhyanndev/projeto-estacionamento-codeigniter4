<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompanyModel;
use App\Validation\CompanyValidation;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class CompanyController extends BaseController
{

    // Instanciando o modelo
    private CompanyModel $companyModel;

    public function __construct()
    {
        // Uso do Factory, fabricando uma instância de um novo objeto da classe CompanyModel
        $this->companyModel = model(CompanyModel::class);
    }

    public function index(): string
    {
        $this->dataToView['title'] = 'Gerenciar informações da empresa';
        return view('Company/index', $this->dataToView);
    }

    public function process(): RedirectResponse
    {

        $rules = (new CompanyValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()->back()->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        // Buscando o registro na base de dados para ser populado
        // Populando cada uma das propriedades do objeto com os dados que estão vindo do request
        $company = $this->companyModel->getCompany();
        $company->fill($this->validator->getValidated());

        // Trabalhando com entidades
        // Armazena os valores originais e compara com os novos valores
        if($company->hasChanged()){

            $this->companyModel->save($company);
        }

        return redirect()->back()->with('success', 'Sucesso');
        
    }
}
