<?php

namespace App\Validation;

class CategoryValidation
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

            'price_hour' => [
                'label' => 'Preço por hora',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Obrigatório',
                    'required|is_natural_no_zero' => 'Informe um valor maior que zero',
                ]
            ],

            'price_day' => [
                'label' => 'Preço por dia',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Obrigatório',
                    'required|is_natural_no_zero' => 'Informe um valor maior que zero',
                ]
            ],

            'price_month' => [
                'label' => 'Preço por mês',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Obrigatório',
                    'required|is_natural_no_zero' => 'Informe um valor maior que zero',
                ]
            ],

            'spots' => [
                'label' => 'Número de vagas',
                'rules' => 'required|is_natural_no_zero',
                'errors' => [
                    'required' => 'Obrigatório',
                    'required|is_natural_no_zero' => 'Informe um valor maior que zero',
                ]
            ],

        ];
    }
}
