<?php

use CodeIgniter\Router\RouteCollection;

use App\Controllers\Auth;
use App\Controllers\DashboardController;
use App\Controllers\TravelOrderController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');




//auth routes
$routes->group('', ['filter' => 'noauth'], function ($routes) {


    $routes->get('/login', [Auth::class, 'logIn'], ['as' => 'login']);
    $routes->post('/login/auth', [Auth::class, 'auth'], ['as' => 'auth.submit']);
    $routes->get('google/login', [Auth::class, 'googleLogin'], ['as' => 'google.login']);
    $routes->get('google/callback', [Auth::class, 'callback'], ['as' => 'google.callback']);
});

// Authenticated routes
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('logout', [Auth::class, 'logout']);

    // Begin :: dashboard route
    $routes->get('dashboard', [DashboardController::class, 'index'],['as' => 'dashboard']);
    // End :: Dashboard route

    $routes->post('dashboard/create-travel-order', [TravelOrderController::class, 'createTravelOrder'], ['as' => 'create.TravelOrder']);

    $routes->post('dashboard/mytravelorders', [TravelOrderController::class, 'travelOrdersData'], ['as' => 'data.travelOrders']);

});