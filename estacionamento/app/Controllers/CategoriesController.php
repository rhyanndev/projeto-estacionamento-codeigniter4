<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Mongo\CategoryModel;
use App\Validation\CategoryValidation;
use CodeIgniter\HTTP\RedirectResponse;
use MongoDB\Model\BSONDocument;

class CategoriesController extends BaseController
{
    private const VIEWS_DIRECTORY = 'Categories/';

    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index(): string
    {

        // Criando variáveis e atribuindo valores
        $this->dataToView['title']      = 'Gerenciar categorias';
        $this->dataToView['categories'] = $this->categoryModel->all();

        return view(self::VIEWS_DIRECTORY . 'index', $this->dataToView);
    }


    public function new(): string
    {
        //Criando variáveis e atribuindo valores
        $this->dataToView['title'] = 'Nova categoria';
        $this->dataToView['category'] = new BSONDocument();

        return view(self::VIEWS_DIRECTORY . 'new', $this->dataToView);
    }

    public function create(): RedirectResponse
    {

        $this->allowedMethod('post');

        $rules = (new CategoryValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = $this->prepareData();

        if (!$this->categoryModel->create($data)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('categories')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(string $id): string
    {
        
        $category = $this->categoryModel->findOrFail($id);

        $this->dataToView['title'] = 'Editar categoria';
        $this->dataToView['category'] = $category;

        return view(self::VIEWS_DIRECTORY . 'edit', $this->dataToView);
    }

    public function update(string $id): RedirectResponse
    {

        $this->allowedMethod('put');

        $rules = (new CategoryValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()
                ->back()
                ->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = $this->prepareData();

        if (!$this->categoryModel->update(id: $id, data: $data)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('categories')->with('success', 'Categoria criada com sucesso!');
    }

    public function delete(string $id): RedirectResponse
    {

        $this->allowedMethod('delete');

        if (!$this->categoryModel->delete(id: $id)) {

            return redirect()
                ->back()
                ->with('danger', 'Ocorreu um erro aqui do nosso lado. Por favor tente mais tarde');
        }

        return redirect()->route('categories')->with('success', 'Categoria deletada com sucesso!');
    }


    // Tratando o dados antes deles serem enviados, (passando de string para integer)
    private function prepareData(): array {

        $data = esc($this->validator->getValidated());

        return [
            'name'        => $data['name'],
            'spots'       => intval($data['spots']),
            'price_day'   => intval($data['price_day']),
            'price_month' => intval($data['price_month']),
            'price_hour'  => intval($data['price_hour']),
        ];
    }
}
