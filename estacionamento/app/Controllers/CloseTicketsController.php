<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Mongo\TicketModel;
use App\Libraries\Ticket\PaymentMethodService;
use App\Libraries\Ticket\StoreTicketService;
use App\Libraries\Ticket\TicketCalculationService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class CloseTicketsController extends BaseController
{
    public function close(string $id)
    {

        $ticket = (new TicketModel())->getOrFail($id); 

        // Calculamos os valores
        $ticket = (new TicketCalculationService)->calculate($ticket);

        $this->dataToView['title'] = 'Encerrar ticket';
        $this->dataToView['ticket'] = $ticket;
        $this->dataToView['paymentMethodsOptions'] = (new PaymentMethodService)->options($ticket->amount_due);


        return view('Parking/close', $this->dataToView);
    }

    public function process(string $id): RedirectResponse {

        $ticket = (new TicketModel())->getOrFail($id); 

        $storeService = new StoreTicketService;

        $paymentMethod = (string) $this->request->getPost('payment_method');

        if(! $storeService->close(
            ticket: $ticket, 
            paymentMethod: $paymentMethod
            
        )){

            return redirect()->back()->with('danger', 'Não foi possível processar o encerramento do ticket.');

        }

        $route = route_to('parking.show.ticket')."?ticket_id={$ticket->id()}";

        return redirect()->to($route)->with('success', 'Sucesso!');

    }
}
