<?php

namespace App\Validation;

class MonthlyFeeValidation
{

    public function getRules(): array
    {

        return [

            'category_id' => [
                'label' => 'Categoria',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

            'customer_id' => [
                'label' => 'Cliente',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

            'due_date' => [
                'label' => 'Data de vencimento',
                'rules' => 'required|valid_date[Y-m-d]', // YYYY-mm-dd
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

            'status' => [
                'label' => 'Status',
                'rules' => 'required', 
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

        ];
    }
}
