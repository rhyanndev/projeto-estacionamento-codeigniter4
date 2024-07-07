<?php

declare(strict_types=1);

namespace App\Libraries\Ticket;

use App\Entities\Ticket;
use App\Enum\TicketChoice;
use DateTime;
use InvalidArgumentException;
use stdClass;

class TicketCalculationService
{

    private Ticket $ticket;

    public function calculate(Ticket $ticket): Ticket
    {

        $this->ticket = $ticket;

        $this->setCurrentSituation();

        return $this->ticket;
    }

    // Define algumas propriedades importantes
    private function setCurrentSituation(): void
    {
        // Calculamos o tempo decorrido
        $this->ticket->elapsed_time    = $this->elapsedTime();
        $this->ticket->amount_park     = $this->calculateAmountPark();
        $this->ticket->amount_due      = $this->calculateAmountDue();
        $this->ticket->category_value  = $this->ticket->amount_due > 0 ? $this->getCategoryValue() : 0;
    }

    private function elapsedTime(): string
    {

        if ($this->ticket->isClosed()) {

            return $this->ticket->elapsed_time;
        }

        /* @var object tempo calculado */

        $calculated = $this->calculateElapsedTime();

        // Retornamos algo como: 2h e 43 minutos
        return "{$calculated->hours} horas e {$calculated->minutes} minutos";
    }

    private function calculateAmountPark(): float
    {

        if ($this->ticket->isClosed()) {

            return $this->ticket->amount_park;
        }

        if ($this->ticket->hasCustomer()) {

            return 0;
        }

        $categoryValue = $this->getCategoryValue();

        // Calculo do tempo decorrido

        $calculated = $this->calculateElapsedTime();

        // Se a escolha for diária, então precisamos calcular o dia

        if (TicketChoice::isDaily(choice: $this->ticket->choice)) {

            // Número de minutos em um dia
            $minutesInOneDay = 1440;

            $days = (($calculated->hours * 60 + $calculated->minutes) / $minutesInOneDay);

            $totalCost = $days * $categoryValue;

            return $totalCost;
        }

        // Nesse ponto sabemos que é por hora...

        $totalHours = $calculated->hours + ($calculated->minutes / 60);
        $totalCost  = $totalHours * $categoryValue;

        return $totalCost;
    }

    private function calculateElapsedTime(): object
    {

        $createdAt   = new DateTime($this->ticket->created_at->format('Y-m-d H:i:s'));
        $currentTime = new DateTime();

        $diference = $currentTime->diff($createdAt);

        $hours =  $diference->h;
        $hours += ($diference->days * 24);

        return (object) ['hours' => $hours, 'minutes' => $diference->i];
    }

    private function getCategoryValue(): int
    {

        if ($this->ticket->isClosed()) {

            return $this->ticket->category_value;
        }

        $category = $this->ticket->category;

        if ($category === null) {

            throw new InvalidArgumentException('A categoria associada ao ticket não pode estar null ou não foi localizada');
        }

        return match ($this->ticket->choice) {

            // Uso do enumeration

            TicketChoice::Hour->value  => $category->price_hour,
            TicketChoice::Day->value   => $category->price_day,
            TicketChoice::Month->value => $category->price_month,
            default => throw new InvalidArgumentException("O tipo {$this->ticket->choice} não é suportado")
        };
    }

    private function calculateAmountDue(): int|float
    {

        if ($this->ticket->isClosed()) {

            return $this->ticket->amount_paid;
        }

        return $this->calculateAmountPark();
    }
}
