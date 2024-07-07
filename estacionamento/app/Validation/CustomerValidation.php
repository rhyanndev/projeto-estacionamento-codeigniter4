<?php

namespace App\Validation;

class CustomerValidation
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

            'cpf' => [
                'label' => 'CPF',
                'rules' => 'required|exact_length[14]',
                'errors' => [
                    'required'   => 'Obrigatório',
                    'exact_length' => 'Informe exatamente 14 caractéres',
                ]
            ],

            'email' => [
                'label' => 'E-mail',
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

        ];
    }
}
