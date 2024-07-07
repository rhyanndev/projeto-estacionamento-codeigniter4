<?php

declare(strict_types=1);

namespace App\Traits;

use App\Libraries\Mongo\CategoryModel;

trait CategoryDropdownTrait {

    public function categoryDropdown(?string $categoryId = null): string {

        $documents = (new CategoryModel)->all();

        if(empty($documents)){

            return form_dropdown(
                data: '',
                options: ['' => 'Não há categorias disponíveis'],
                extra: [
                    'class' => 'form-control', 
                    'required' => true, 
                    'disabled' => true,
                    'id'       => 'category_id'
                ],

            );
        }

        $options = [];
        $options[null] = '--- Escolha ---';

        foreach($documents as $category){

            $options[(string) $category->_id] = $category->name;
        }

        return form_dropdown(
            data: 'category_id',
            options: $options,
            selected: $categoryId,
            extra: [
                'class' => 'form-control', 
                'required' => true, 
                'id'       => 'category_id'
            ],

        );

    }
}