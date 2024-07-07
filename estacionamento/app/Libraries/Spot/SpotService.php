<?php

declare(strict_types=1);

namespace App\Libraries\Spot;

use App\Enum\TicketStatus;
use App\Libraries\Mongo\CategoryModel;
use App\Libraries\Mongo\TicketModel;
use stdClass;

class SpotService
{


    private const COMMON_BTN_CLASSES = 'btn-style-park btn position-relative fw-bold p-1';

    private const CLASS_BTN_FOR_CREATE_TICKET = 'btn-new-ticket';

    private const CLASS_BTN_FOR_VIEW_TICKET = 'btn-view-ticket';

    private const CLASS_BTN_FOR_OCCUPIED_SPOT = 'small-font-plate btn-dark';

    private const CLASS_BTN_FOR_SPOT_FREE = 'btn-spot-free';



    public function getSpots(): string
    {

        $categories = $this->preparedSpots();

        if (empty($categories)) {

            return '
                    <div class="container">
                        <div class="alert alert-info text-center">
                            <strong>Não há categorias disponíveis</strong>
                        </div>
                    </div>
                   ';
        }

        $divCols = '';
        foreach ($categories as $category) {

            $divCols .= $this->generateCategoryHtml(category: $category);
        }

        return $divCols;
    }

    private function generateCategoryHtml(object $category): string
    {

        return "<div class='col-md grid-margin stretch-card'>  

                    <div class='card pt-3'>
                        <h4 class='text-center card-title'>
                            {$category->name}
                        </h4>
                        <ul class='list-inline text-center pt-2'>
                            {$this->generateLiElementsHtml(category:$category)}
                        </ul>
                    </div>

                </div>";
    }

    private function generateLiElementsHtml(object $category): string
    {

        $liElement = '';

        foreach ($category->spots as $spot) {

            $liElement .= "<li class= 'list-inline-item m-1'>
                            {$this->generateButtonParkHtml(category: $category, spot: $spot)}
                            </li>";
        }

        return $liElement;
    }

    private function generateButtonParkHtml(object $category, int|string|object $spot): string
    {   

        if(is_object($spot)) {
            return $this->generateOcuppiedSpotButtonHtml(spot: $spot);
        }

        return $this->generateFreeSpotButtonHtml(category: $category, spot: $spot);
    }

    private function generateOcuppiedSpotButtonHtml(object $spot): string
    {
        $class = '';
        $class .= self::COMMON_BTN_CLASSES;
        $class .= " "; // Concatenamos um espaço
        $class .= self::CLASS_BTN_FOR_OCCUPIED_SPOT; 
        $class .= " "; // Concatenamos um espaço
        $class .= self::CLASS_BTN_FOR_VIEW_TICKET;

        // Hover no botão
        return form_button([
            'type'      => 'button',
            'class'     => $class,
            'title'     => $spot->vehicle,
            'data-code' => (string) $spot->ticket_code,
            'content'   => "{$spot->plate} {$spot->type}" // o que será exibido
        ]);
    }

    private function generateFreeSpotButtonHtml(object $category, int|string|object $spot): string
    {
        $class = '';
        $class .= self::COMMON_BTN_CLASSES;
        $class .= " "; // Concatenamos um espaço
        $class .= self::CLASS_BTN_FOR_SPOT_FREE; 
        $class .= " "; // Concatenamos um espaço
        $class .= self::CLASS_BTN_FOR_CREATE_TICKET;

        // Hover no botão
        return form_button([
            'type'      => 'button',
            'class'     => $class,
            'title'     => 'Vaga livre',
            'data-code' => (string) $category->code,
            'data-spot' => $spot,
            'content'   => $spot // o que será exibido
        ]);
    }

    private function preparedSpots(): array
    {
        // Vai preparar as vagas, buscar as categorias na base de dados (Mongo)
        $categories = (new CategoryModel)->all();

        if (empty($categories)) {

            return [];
        }

        //Tabela para armazenar os tickets em aberto

        /**
         * recuperar os ticket em aberto
         */

        $ticketModel = new TicketModel();

        $openTickets = $ticketModel->getAll(
            filter: ['status' => TicketStatus::Open->value]
        );
        
        $categoriesPrepared = [];

        foreach ($categories as $category) {

            $categoriesPrepared[] = $this->preparedCategory(category: $category, openTickets: $openTickets);
        }

        return $categoriesPrepared;
    }

    private function preparedCategory(object $category, array $openTickets): object
    {

        $spotsCategory = [];

        for ($spot = 1; $spot <= $category->spots; $spot++) {

            $spotsCategory[$spot] = $spot;
            $this->addTicketDataToSpot(
                spotsCategory: $spotsCategory,
                spot: $spot,
                category: $category,
                openTickets: $openTickets
            );
        }

        return (object) [
            'code'  => (string) $category->_id,
            'name'  => $category->name,
            'spots' => $spotsCategory,

        ];
    }

    private function addTicketDataToSpot(
        array &$spotsCategory,
        int $spot,
        object $category,
        array $openTickets
    ): void {

        // Percorremos os tickets em aberto
        foreach ($openTickets as $ticket) {

            //convertemos os IDs para os tipos apropriados
            $categoryId     = (string) $category->_id;
            $ticketCategory = (string) $ticket->category->_id;
            $ticketSpot     = (int) $ticket->spot;

            // verificamos se a categoria e a vaga correspondem a um ticket
            if ($categoryId === $ticketCategory && $spot === $ticketSpot) {

                // Adiciono informações do ticket à vaga
                $spotsCategory[$spot] = (object) [

                    'plate'       => $ticket->plate,
                    'ticket_code' => (string) $ticket->_id,
                    'vehicle'     => $ticket->vehicle,
                    'type'        => empty($ticket->customer) ? 'Avulso' : 'Mensalista',
                ];
            }
        }
    }
}
