<?php

namespace App\Validation;

class SingleTicketValidation
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

            'choice' => [
                'label' => 'Tipo',
                'rules' => 'required|in_list[hour, day]',
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

            'plate' => [
                'label' => 'Placa',
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
