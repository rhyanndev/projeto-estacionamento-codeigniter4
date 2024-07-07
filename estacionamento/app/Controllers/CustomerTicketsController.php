<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Mongo\CarModel;
use App\Libraries\Mongo\CategoryModel;
use App\Libraries\Ticket\StoreTicketService;
use App\Traits\CustomerDropdownTrait;
use App\Validation\CustomerTicketValidation;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerTicketsController extends BaseController
{
    use CustomerDropdownTrait;

    private const VIEWS_DIRECTORY = 'Parking/Customer/';

    public function new()
    {
        $categoryId = (string) $this->request->getGet('code');
        $spot       = (string) $this->request->getGet('spot'); //$spot = (int) -> como estava. Gerando erro no hidden

        if (empty($categoryId) || $spot < 1) {

            return redirect()->back()->with('info', 'Não conseguimos identificar a categoria');
        }

        $category = (new CategoryModel)->findOrFail($categoryId);

        $this->dataToView['title']            = 'Criar ticket mensalista';
        $this->dataToView['category']         = $category;
        $this->dataToView['customersOptions'] = $this->customersDropdown();
        $this->dataToView['hidden']           = [
            'spot' => $spot,
            'category_id' => (string) $category['_id'],


            // usaremos no script e no armazenamento
            'hidden_plate'   => '',
            'hidden_vehicle' => '',
        ];

        return view(self::VIEWS_DIRECTORY . 'new', $this->dataToView);
    }

    public function cars(): Response
    {

        $this->allowedMethod('ajax');

        $customerId = (string) $this->request->getGet('customer_code');

        $cars = (new CarModel())->allByCustomerId($customerId);

        /** 
         * @todo verificar se o cliente é devedor
         * 
         */

        $block = false;

        return $this->response->setJSON([
            'cars'  => $cars,
            'block' => $block, // usamos no script para bloquear ou liberar a criação de ticket
        ]);
    }

    public function create(): RedirectResponse
    {

        $this->allowedMethod('post');

        $rules = (new CustomerTicketValidation)->getRules();

        if (!$this->validate($rules)) {

            return redirect()->back()->with('danger', 'Verifique os erros e tente novamente')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $storeService = new StoreTicketService;

        if (!$storeService->createForCustomer(validatedData: $this->validator->getValidated())) {

            return redirect()->back()->with('danger', 'Não foi possível criar o ticket avulso');
        }

        return redirect()->route('parking')->with('success', 'Sucesso!');
    }
}
