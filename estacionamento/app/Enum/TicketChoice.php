<?php

declare(strict_types=1);

namespace App\Enum;

enum TicketChoice: string
{

    case Hour  = 'hour';
    case Day   = 'day';
    case Month = 'month';

    public function toString(): string
    {

        return match ($this) {

            self::Hour   => 'Por hora',
            self::Day    => 'Diário',
            self::Month  => 'Mensalista',
            default      => 'Desconhecido',
        };
    }

    public static function isDaily(string $choice): bool {

        return self::Day->value === $choice;

    }
}
