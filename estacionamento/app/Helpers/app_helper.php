<?php

// Formatando o valor no real brasileiro

use App\Enum\MonthlyFeeStatus;

if (!function_exists('format_money_brl')) {

    function format_money_brl(float|int $num): string
    {

        return number_to_currency(num: $num/100, currency: 'BRL', fraction: 2);
    }
}

// Para verificar situações em que a mensalidade está paga
if (!function_exists('fee_is_paid')) {

    function fee_is_paid(?string $status = null): string
    {

        if($status === null){

            return false;

        }

        return $status === MonthlyFeeStatus::Paid->value;
    }
}
