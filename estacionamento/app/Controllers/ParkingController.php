<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Mongo\TicketModel;
use App\Libraries\Spot\SpotService;
use App\Libraries\Ticket\TicketCalculationService;
use CodeIgniter\HTTP\ResponseInterface;

class ParkingController extends BaseController
{
    private const VIEWS_DIRECTORY = 'Parking/';

    private SpotService $spotService;

    public function __construct()
    {
        $this->spotService = new SpotService();
    }

    public function index(): string
    {
        
        $this->dataToView['title'] = 'Disposição das vagas';
        $this->dataToView['spots'] = $this->spotService->getSpots(); 

        return view(self::VIEWS_DIRECTORY. 'index', $this->dataToView);
    }


    public function show(): string {

        $ticketId = (string) $this->request->getGet('ticket_id');

        $ticket = (new TicketModel())->getOrFail($ticketId); 

        // Calculamos os valores
        $ticket = (new TicketCalculationService)->calculate($ticket);

        $this->dataToView['title'] = 'Detalhes do ticket';
        $this->dataToView['ticket'] = $ticket;

        return view(self::VIEWS_DIRECTORY. 'show', $this->dataToView);
    }
}
