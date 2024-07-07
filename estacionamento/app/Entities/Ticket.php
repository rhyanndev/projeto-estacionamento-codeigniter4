<?php

namespace App\Entities;

use App\Enum\PaymentMethod;
use App\Enum\TicketChoice;
use App\Enum\TicketStatus;
use CodeIgniter\Entity\Entity;

class Ticket extends Entity
{
    protected $dates   = ['created_at', 'updated_at'];

    public function id(): string
    {

        return (string) $this->_id;
    }

    public function status(): string
    {

        return TicketStatus::tryFrom($this->status)->toString();
    }

    public function isClosed(): bool
    {

        return $this->status === TicketStatus::Closed->value;
    }

    public function car(): string
    {

        // Se está vazia a propriedade customer, vai retornar os dados do ticket avulso
        if (!$this->hasCustomer()) {

            return "{$this->vehicle} | {$this->plate}";
        }

        // Aqui sabemos que temos um customer, Logo é ticket mensalista
        // Existe no meu objeto o carro? pode acessar o carro e tente acessar também nesse objeto a placa do carro
        return $this?->car?->vehicle . ' | ' . $this?->car->plate;
    }

    public function category(): string
    {

        return $this?->category?->name ?? '<span class="badge badge-danger">Não localizada</span>';
    }

    public function type(): string
    {

        return $this->type === TicketChoice::Month->value ? 'Mensalista' : 'Avulso';
    }

    public function choice(): string
    {

        return TicketChoice::tryFrom($this->choice)->toString();
    }

    public function paymentMethod(): string
    {

        if (!$this->isClosed()) {

            return $this->status();
        }

        return PaymentMethod::tryFrom($this->payment_method)->toString();
    }

    
    // Verificar se existe um cliente associado ao meu ticket

    public function hasCustomer(): bool {

        return $this->customer !== null;
    }

    public function observations(): string {

        return $this->observations ?? 'Sem observações';
    }
}
