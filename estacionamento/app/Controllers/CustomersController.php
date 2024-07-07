<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Mongo\CustomerModel;
use App\Validation\CustomerValidation;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use MongoDB\Model\BSONDocument;

class CustomersController extends BaseController
{
    private const VIEWS_DIRECTORY = 'Customers/';

    private CustomerModel $customerModel;

    public function __construct()
    {

        $this->customerModel = new CustomerModel();
    }

    public function index(): string
    {
        $this->dataToView['title']      = 'Gerenciar mensalistas';
        $this->dataToView['customers']      = $this->customerModel->all();

        return view(self::VIEWS_DIRECTORY . 'index', $this->dataToView);
    }

    public function new(): string
    {
        $this->dataToView['title']      = 'Criar mensalistas';
        $this->dataToView['customer']   = new BSONDocument();

        return view(self::VIEWS_DIRECTORY . 'new', $this->dataToView);
    }

    public function create(): RedirectResponse
    {

        $rules = (new CustomerValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        if (!$this->customerModel->create(data: $this->validator->getValidated())) {

            return redirect()
                ->back()
                ->with('danger', 'Não foi possível criar o registro, por favor tente mais tarde')
                ->withInput();
        }

        return redirect()
            ->route('customers')
            ->with('success', 'Sucesso!');
    }

    public function show(string $id): string
    {
        $customer = $this->customerModel->findOrFail($id);
        $this->dataToView['title']      = 'Detalhes mensalistas';
        $this->dataToView['customer']   =  $customer;

        return view(self::VIEWS_DIRECTORY . 'show', $this->dataToView);
    }

    public function edit(string $id): string
    {
        $customer = $this->customerModel->findOrFail($id);
        $this->dataToView['title']      = 'Editar mensalistas';
        $this->dataToView['customer']   =  $customer;

        return view(self::VIEWS_DIRECTORY . 'edit', $this->dataToView);
    }

    public function update(string $id): RedirectResponse
    {

        $this->allowedMethod('put');

        $rules = (new CustomerValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        if (!$this->customerModel->update(id: $id, data: $this->validator->getValidated())) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('customers')->with('success', 'Informação atualizada com sucesso!');
    }

    public function delete(string $id): RedirectResponse
    {

        $this->allowedMethod('delete');

        if (!$this->customerModel->delete(id: $id)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('customers')->with('success', 'Mensalista deletado com sucesso!');
    }
}
