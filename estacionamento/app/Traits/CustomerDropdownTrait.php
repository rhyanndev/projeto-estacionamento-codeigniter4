<?php

declare(strict_types=1);

namespace App\Traits;

use App\Libraries\Mongo\CustomerModel;

trait CustomerDropdownTrait {

    public function customersDropdown(?string $customerId = null): string {

        $documents = (new CustomerModel)->all();

        if(empty($documents)){

            return form_dropdown(
                data: '',
                options: ['' => 'Não há mensalistas disponíveis'],
                extra: [
                    'class' => 'form-control', 
                    'required' => true, 
                    'disabled' => true,
                    'id'       => 'customer_id'
                ],

            );
        }

        $options = [];
        $options[null] = '--- Escolha ---';

        foreach($documents as $customer){

            $options[(string) $customer->_id] = "{$customer->name} - CPF: {$customer->cpf}";
        }

        return form_dropdown(
            data: 'customer_id',
            options: $options,
            selected: $customerId,
            extra: [
                'class' => 'form-control', 
                'required' => true, 
                'id'       => 'customer_id'
            ],

        );

    }
}