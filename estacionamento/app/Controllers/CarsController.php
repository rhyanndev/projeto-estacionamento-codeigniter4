<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Mongo\CarModel;
use App\Libraries\Mongo\CustomerModel;
use App\Validation\CarValidation;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;
use PhpParser\Node\Expr\Cast\Object_;

class CarsController extends BaseController
{
    private const VIEWS_DIRECTORY = 'Cars/';

    private CustomerModel $customerModel;
    private CarModel $carModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->carModel      = new CarModel();
    }

    public function all(string $customerId) : string
    {
        //Buscar o carro do mensalista na base dados

        $customer = $this->customerModel->findOrFail($customerId);

        $this->dataToView['title']    = "Carros do mensalista {$customer['name']}";
        $this->dataToView['customer'] = $customer;
        $this->dataToView['cars']     = $customer['cars'] ?? [];
        
        return view(self::VIEWS_DIRECTORY. 'all', $this->dataToView);

    }

    public function new(string $customerId) : string
    {

        $this->dataToView['title']       = 'Novo carro';
        $this->dataToView['customer_id'] = $customerId;
        $this->dataToView['car']         = new BSONDocument();
        
        return view(self::VIEWS_DIRECTORY. 'new', $this->dataToView);
    }

    public function create(): RedirectResponse
    {
        $this->allowedMethod('post');

        $rules = (new CarValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = $this->validator->getValidated();
        $data['customer_id'] = new ObjectId($data['customer_id']);

        if (!$this->carModel->create(data: $data)) {

            return redirect()
                ->back()
                ->with('danger', 'Não foi possível criar o registro, por favor tente mais tarde')
                ->withInput();
        }

        return redirect()
            ->back()
            ->with('success', 'Sucesso!');
    }

    public function edit(string $id) : string
    {

        $car = $this->carModel->findOrFail($id);

        $this->dataToView['title']       = 'Editar carro';
        $this->dataToView['customer_id'] = (string) $car['customer_id'];
        $this->dataToView['car']         = $car;
        
        return view(self::VIEWS_DIRECTORY. 'edit', $this->dataToView);
    }

    public function update(string $id): RedirectResponse
    {
        $this->allowedMethod('put');

        $rules = (new CarValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = $this->validator->getValidated();
        $customerId = (string) $data['customer_id'];
        unset($data['customer_id']);

        if (!$this->carModel->update(id: $id, data: $data)) {

            return redirect()
                ->back()
                ->with('danger', 'Não foi possível criar o registro, por favor tente mais tarde')
                ->withInput();
        }

        return redirect()
            ->route('customers.cars', [$customerId])
            ->with('success', 'Sucesso!');
    }

    public function delete(string $id): RedirectResponse
    {

        $this->allowedMethod('delete');

        if (!$this->carModel->delete(id: $id)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->back()->with('success', 'Carro deletado com sucesso!');
    }

}
