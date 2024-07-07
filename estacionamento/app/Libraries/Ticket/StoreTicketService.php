<?php

declare(strict_types=1);

namespace App\Libraries\Ticket;

use App\Enum\PaymentMethod;
use App\Enum\TicketStatus;
use App\Entities\Ticket;
use App\Enum\TicketChoice;
use App\Libraries\Mongo\TicketModel;
use CodeIgniter\I18n\Time;
use MongoDB\BSON\ObjectId;

class StoreTicketService
{

    private ?array $validatedData;

    private TicketModel $ticketModel;

    public function __construct()
    {
        $this->validatedData = [];

        $this->ticketModel = new TicketModel();
    }

    public function createSingle(array $validatedData): bool
    {

        $this->validatedData = $validatedData;

        // Criação de dois métodos para geração do array
        $dataToStore = array_merge(

            $this->getCommonData(),
            $this->getSingleData(),

        );

        return $this->ticketModel->create($dataToStore);
    }

    public function createForCustomer(array $validatedData): bool
    {

        $this->validatedData = $validatedData;

        $dataToStore = array_merge(

            $this->getCommonData(),
            $this->getCustomerData(),

        );

        return $this->ticketModel->create($dataToStore);
    }

    public function close(Ticket $ticket, string $paymentMethod): bool
    {

        // Calculamos os valores
        $ticket = (new TicketCalculationService)->calculate($ticket);

        $dataToStore = [
            'payment_method'  => PaymentMethod::from($paymentMethod)->value,
            'status'          => TicketStatus::Closed->value,
            'category_value'  => intval($ticket->category_value),
            'amount_park'     => intval($ticket->amount_park),
            'amount_paid'     => intval($ticket->amount_due),
            'elapsed_time'    => $ticket->elapsed_time,
            'updated_at'      => Time::now()->format('Y-m-d H:i:s')
        ];

        return $this->ticketModel->update(id: $ticket->id(), data: $dataToStore);
    }

    private function getCommonData(): array
    {

        return [

            'category_id' => new ObjectId($this->validatedData['category_id']),
            'spot'        => intval($this->validatedData['spot']),
            'status'      => TicketStatus::Open->value,
            'created_at'  => Time::now()->format('Y-m-d H:i:s'), //2024-07-04

        ];
    }

    private function getSingleData(): array
    {

        return [
            'vehicle'        => $this->validatedData['vehicle'],
            'plate'          => $this->validatedData['plate'],
            'observations'   => $this->validatedData['observations'],
            'choice'         => $this->validatedData['choice'],

        ];
    }

    private function getCustomerData(): array
    {
        // Dados referentes ao cliente
        return [
            'customer_id'   => new ObjectId($this->validatedData['customer_id']),
            'car_id'        => new ObjectId($this->validatedData['car_id']),
            'choice'        => TicketChoice::Month->value, //mensal
            'plate'         => $this->validatedData['hidden_plate'],
            'vehicle'       => $this->validatedData['hidden_vehicle'],

        ];  
    }
}
