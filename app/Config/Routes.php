<?php

use CodeIgniter\Router\RouteCollection;

use App\Controllers\Auth;
use App\Controllers\DashboardController;
use App\Controllers\TravelOrderController;
use App\Controllers\ProfileController;

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
    $routes->post('dashboard/create-travel-order', [TravelOrderController::class, 'createTravelOrder'], ['as' => 'create.TravelOrder']);
    $routes->post('dashboard/mytravelorders', [TravelOrderController::class, 'travelOrdersData'], ['as' => 'data.travelOrders']);
    
    $routes->get('dashboard/travel-orders/details/(:num)',[TravelOrderController::class, 'travelOrderDetails'],['as' => 'data.travelOrderDetails']);
    // End :: Dashboard route

    $routes->group('account-settings', function ($routes) {
        $routes->get('/', [ProfileController::class, 'settings'], ['as' => 'account-settings']);
        $routes->post('update-account-info', [ProfileController::class, 'updateAccountInfo'], ['as' => 'user.update-account-info']);
        $routes->post('update-profile-picture', [ProfileController::class, 'updateProfilePicture'], ['as' => 'user.update-profile-picture']);
        $routes->post('change-password', [ProfileController::class, 'changePassword'], ['as' => 'user.change-password']);
        $routes->post('delete-account', [ProfileController::class, 'deleteAccount'], ['as' => 'user.delete-account']);
        $routes->post('update-first-name', [ProfileController::class, 'updateFirstName'], ['as' => 'update.firstname']);
        $routes->post('update-last-name', [ProfileController::class, 'updateLastName'], ['as' => 'update.lastname']);
        $routes->post('update-email', [ProfileController::class, 'updateEmail'], ['as' => 'update.email']);
        $routes->post('update-birthdate', [ProfileController::class, 'updateBirthday'], ['as' => 'update.birthdate']);
        $routes->post('update-gender', [ProfileController::class, 'updateGender'], ['as' => 'update.gender']);
});

});