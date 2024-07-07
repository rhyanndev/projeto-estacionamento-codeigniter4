<?php

namespace App\Validation;

class CompanyValidation
{

    public function getRules(): array
    {

        return [

            'name' => [
                'label' => 'Nome',
                'rules' => 'required|max_length[128]',
                'errors' => [
                    'required' => 'Obrigatório',
                    'max_length' => 'Informe no máximo 128 caractéres',
                ]
            ],

            'phone' => [
                'label' => 'Telefone',
                'rules' => 'required|max_length[20]',
                'errors' => [
                    'required' => 'Obrigatório',
                    'max_length' => 'Informe no máximo 20 caractéres',
                ]
            ],

            'address' => [
                'label' => 'Endereço',
                'rules' => 'required|max_length[128]',
                'errors' => [
                    'required' => 'Obrigatório',
                    'max_length' => 'Informe no máximo 128 caractéres',
                ]
            ],

            'message' => [
                'label' => 'Mensagem',
                'rules' => 'required|max_length[2000]',
                'errors' => [
                    'required' => 'Obrigatório',
                    'max_length' => 'Informe no máximo 2000 caractéres',
                ]
            ],
        ];
    }
}
