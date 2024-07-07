<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    private const VIEWS_DIRECTORY = 'Home/';

    public function index(): string
    {
        //Criando variáveis e atribuindo valores
        $this->dataToView['title'] = 'Você está na Home';
        $this->dataToView['curso'] = 'Curso de Codeigniter';

        return view(self::VIEWS_DIRECTORY.'index', $this-> dataToView);
    }
}
