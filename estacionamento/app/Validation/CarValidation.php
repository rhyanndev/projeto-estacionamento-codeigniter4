<?php

namespace App\Validation;

class CarValidation
{

    public function getRules(): array
    {

        return [

            'plate' => [
                'label' => 'Placa',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

            'vehicle' => [
                'label' => 'Descrição do veículo',
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

        ];
    }
}
