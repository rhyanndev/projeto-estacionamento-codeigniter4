<?php

namespace App\Validation;

class CustomerTicketValidation
{

    public function getRules(): array
    {

        return [

            'spot' => [
                'label' => 'Vaga',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

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

            'car_id' => [
                'label' => 'Carro',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

            'hidden_plate' => [
                'label' => 'Placa do Carro',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

            'hidden_vehicle' => [
                'label' => 'Descrição do Carro',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Obrigatório',
                ]
            ],

            'observations' => [
                'label' => 'Observações',
                'rules' => 'permit_empty|max_length[500]',
                'errors' => [
                    'required' => 'Máximo 500 caractéres',
                ]
            ],

        ];
    }
}
