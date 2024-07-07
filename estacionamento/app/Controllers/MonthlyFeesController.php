<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Enum\MonthlyFeeStatus;
use App\Libraries\Mongo\MonthlyFeeModel;
use App\Traits\CustomerDropdownTrait;
use App\Traits\CategoryDropdownTrait;
use App\Validation\MonthlyFeeValidation;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;

class MonthlyFeesController extends BaseController
{
    use CategoryDropdownTrait;
    use CustomerDropdownTrait;


    /** @var MonthlyFeeModel */
    private const VIEWS_DIRECTORY = 'MonthlyFees/';

    private MonthlyFeeModel $model;

    public function __construct()
    {
        $this->model = new MonthlyFeeModel();
    }

    public function index(): string
    {
        $this->dataToView['title'] = 'Gerenciar mensalidades';
        $this->dataToView['fees']  = $this->model->all();

        return view(self::VIEWS_DIRECTORY . 'index', $this->dataToView);
    }

    public function new(): string
    {
        $this->dataToView['title'] = 'Nova mensalidade';
        $this->dataToView['fee'] = new BSONDocument();
        $this->dataToView['customersOptions'] = $this->customersDropdown();
        $this->dataToView['categoriesOptions'] = $this->categoryDropdown();


        return view(self::VIEWS_DIRECTORY . 'new', $this->dataToView);
    }

    public function create(): RedirectResponse
    {

        $this->allowedMethod('post');

        $rules = (new MonthlyFeeValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = $this->prepareData();

        if (!$this->model->create($data)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('monthly.fees')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(string $id): string
    {
        $fee = $this->model->findOrFail($id);

        $this->dataToView['title'] = 'Editar mensalidade';
        $this->dataToView['fee'] = $fee;
        $this->dataToView['customersOptions'] = $this->customersDropdown((string) $fee['customer']->_id);
        $this->dataToView['categoriesOptions'] = $this->categoryDropdown((string) $fee['category']->_id);


        return view(self::VIEWS_DIRECTORY . 'edit', $this->dataToView);
    }

    public function update(string $id): RedirectResponse
    {

        $this->allowedMethod('put');

        $rules = (new MonthlyFeeValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = $this->prepareData();

        if (!$this->model->update(id: $id, data: $data)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('monthly.fees')->with('success', 'Categoria criada com sucesso!');
    }

    public function delete(string $id): RedirectResponse
    {

        $this->allowedMethod('delete');

        if (!$this->model->delete(id: $id)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('monthly.fees')->with('success', 'Categoria criada com sucesso!');
    }

    private function prepareData(): array {

        $data = esc($this->validator->getValidated());

        return [
            'category_id' => new ObjectId($data['category_id']),
            'customer_id' => new ObjectId($data['customer_id']),
            'due_date'    => $data['due_date'],
            'status'      => MonthlyFeeStatus::from((string) $data['status'])->value,

        ];
    }

}
