<?php

use App\Controllers\CarsController;
use App\Controllers\CategoriesController;
use App\Controllers\CloseTicketsController;
use App\Controllers\CompanyController;
use App\Controllers\CustomersController;
use App\Controllers\CustomerTicketsController;
use App\Controllers\HomeController;
use App\Controllers\MonthlyFeesController;
use App\Controllers\ParkingController;
use App\Controllers\SingleTicketsController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Trazendo o lugar que está sendo requisitado, método index (controlador), e o apelido para a rota
// De onde está requisitando e para onde vai

$routes->get('/', [HomeController::class, 'index'], ['as' => 'home']);

$routes->group('categories', static function ($routes) {
    $routes->get('/', [CategoriesController::class, 'index'], ['as' => 'categories']);
    $routes->get('new', [CategoriesController::class, 'new'], ['as' => 'categories.new']);
    $routes->post('create', [CategoriesController::class, 'create'], ['as' => 'categories.create']);
    $routes->get('edit/(:segment)', [CategoriesController::class, 'edit/$1'], ['as' => 'categories.edit']);
    $routes->put('update/(:segment)', [CategoriesController::class, 'update/$1'], ['as' => 'categories.update']);
    $routes->delete('delete/(:segment)', [CategoriesController::class, 'delete/$1'], ['as' => 'categories.delete']);
});

$routes->group('customers', static function ($routes) {
    $routes->get('/', [CustomersController::class, 'index'], ['as' => 'customers']);
    $routes->get('new', [CustomersController::class, 'new'], ['as' => 'customers.new']);
    $routes->post('create', [CustomersController::class, 'create'], ['as' => 'customers.create']);
    $routes->get('show/(:segment)', [CustomersController::class, 'show/$1'], ['as' => 'customers.show']);
    $routes->get('edit/(:segment)', [CustomersController::class, 'edit/$1'], ['as' => 'customers.edit']);
    $routes->put('update/(:segment)', [CustomersController::class, 'update/$1'], ['as' => 'customers.update']);
    $routes->delete('delete/(:segment)', [CustomersController::class, 'delete/$1'], ['as' => 'customers.delete']);

    // Carro dos clientes mensalistas
    $routes->group('cars', static function ($routes) {

        $routes->get('all/(:segment)', [CarsController::class, 'all/$1'], ['as' => 'customers.cars']);
        $routes->get('new/(:segment)', [CarsController::class, 'new/$1'], ['as' => 'customers.cars.new']);
        $routes->get('edit/(:segment)', [CarsController::class, 'edit/$1'], ['as' => 'customers.cars.edit']);

        // Create não necessita de um identificador do cliente, vai em um campo escondido do formulário
        $routes->post('create', [CarsController::class, 'create'], ['as' => 'customers.cars.create']);

        // Rotas de atualização e remoção
        $routes->put('update/(:segment)', [CarsController::class, 'update/$1'], ['as' => 'customers.cars.update']);
        $routes->delete('delete/(:segment)', [CarsController::class, 'delete/$1'], ['as' => 'customers.cars.delete']);
    });
});

// Informações da empresa
$routes->group('company', static function ($routes) {

    $routes->get('/', [CompanyController::class, 'index'], ['as' => 'company']);
    $routes->match(['post', 'put'], 'process',  [CompanyController::class, 'process'], ['as' => 'company.process']);
});

// Parking - Estacionar
$routes->group('parking', static function ($routes) {

    $routes->get('/', [ParkingController::class, 'index'], ['as' => 'parking']);
    $routes->get('show', [ParkingController::class, 'show'], ['as' => 'parking.show.ticket']);


    // Parking - Estacionar - avulsos
    $routes->group('single', static function ($routes) {

        $routes->get('new', [SingleTicketsController::class, 'new'], ['as' => 'parking.new.single.ticket']);
        $routes->post('create', [SingleTicketsController::class, 'create'], ['as' => 'parking.create.single.ticket']);
    });

    // Parking - Encerrar
    $routes->group('close', static function ($routes) {

        $routes->get('(:segment)', [CloseTicketsController::class, 'close'], ['as' => 'parking.close.ticket']); //view
        $routes->put('process/(:segment)', [CloseTicketsController::class, 'process/$1'], ['as' => 'parking.process.close.ticket']); //processa o encerramento
    });

    // Parking - Estacionar - mensalistas
    $routes->group('customers', static function ($routes) {

        $routes->get('new', [CustomerTicketsController::class, 'new'], ['as' => 'parking.new.customer.ticket']);
        $routes->get('cars', [CustomerTicketsController::class, 'cars'], ['as' => 'parking.get.curstomers.cars']);
        $routes->post('create', [CustomerTicketsController::class, 'create'], ['as' => 'parking.create.customer.ticket']);
    });
});

// Mensalidades
$routes->group('monthly-fees', static function ($routes) {

    $routes->get('/', [MonthlyFeesController::class, 'index'], ['as' => 'monthly.fees']);
    $routes->get('new', [MonthlyFeesController::class, 'new'], ['as' => 'monthly.fees.new']);
    $routes->post('create', [MonthlyFeesController::class, 'create'], ['as' => 'monthly.fees.create']);
    $routes->get('edit/(:segment)', [MonthlyFeesController::class, 'edit/$1'], ['as' => 'monthly.fees.edit']);
    $routes->put('update/(:segment)', [MonthlyFeesController::class, 'update/$1'], ['as' => 'monthly.fees.update']);
    $routes->delete('delete/(:segment)', [MonthlyFeesController::class, 'delete/$1'], ['as' => 'monthly.fees.delete']);
});
