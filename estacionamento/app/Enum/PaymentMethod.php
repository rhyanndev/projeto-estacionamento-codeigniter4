<?php

declare(strict_types=1);

namespace App\Enum;

enum PaymentMethod: string
{

    case CreditCard  = 'credit_card';
    case DebitCard   = 'debit_card';
    case Pix         = 'pix';
    case Money       = 'money';
    case Free        = 'free';

    public function toString(): string
    {

        return match ($this) {

            self::CreditCard   => 'Cartão de crédito',
            self::DebitCard    => 'Cartão de débito',
            self::Pix          => 'Pix',
            self::Money        => 'Dinheiro',
            self::Free         => 'Sem custo',
            default            => 'Desconhecido',
        };
    }
}
