<?php

declare(strict_types=1);

namespace App\Enum;

enum TicketStatus: string
{

    case Open = 'open';
    case Closed = 'closed';

    public function toString(): string
    {

        return match ($this) {

            self::Open   => 'Aberto',
            self::Closed => 'Encerrado',
            default      => 'Desconhecido',
        };
    }
}
