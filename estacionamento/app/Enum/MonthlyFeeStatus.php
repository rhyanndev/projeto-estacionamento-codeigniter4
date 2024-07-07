<?php

declare(strict_types=1);

namespace App\Enum;

enum MonthlyFeeStatus: string
{

    case Paid = 'paid';
    case Waiting = 'waiting';

    public function toString(): string
    {

        return match ($this) {

            self::Paid   => 'Pago',
            self::Waiting => 'Aguardando pagamento',
            default      => 'Desconhecido',
        };
    }
}
