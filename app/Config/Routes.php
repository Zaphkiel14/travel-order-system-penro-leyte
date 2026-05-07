<?php

use CodeIgniter\Router\RouteCollection;

use App\Controllers\Auth;
use App\Controllers\DashboardController;
use App\Controllers\TravelOrderController;
use App\Controllers\ProfileController;
use App\Controllers\ConfigController;
use App\Controllers\UserManagementController;
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
$routes->group('', ['filter' => 'auth'],function ($routes) {
    $routes->get('logout', [Auth::class, 'logout']);
    // Begin :: dashboard route
    $routes->group('dashboard', function ($routes) {
        $routes->get('/', [DashboardController::class, 'index'], ['as' => 'view.dashboard']);
        $routes->post('mytravelorders', [TravelOrderController::class, 'travelOrdersData'], ['as' => 'data.travelOrders']);
        $routes->get('travel-orders/details/(:num)', [TravelOrderController::class, 'travelOrderDetails'], ['as' => 'data.travelOrderDetails']);
        $routes->post('create-travel-order', [TravelOrderController::class, 'createTravelOrder'], ['as' => 'create.TravelOrder']);
        $routes->get('travel-orders/attachment/download/(:any)', [TravelOrderController::class, 'downloadAttachment'], ['as' => 'download.travelOrder']);
        $routes->get('travel-orders/print/(:num)', [TravelOrderController::class, 'printTO/$1'], ['as' => 'print.to']);
        $routes->post('travel-orders/update-attachments/(:num)', [TravelOrderController::class, 'updateAttachments/$1'], ['as' => 'update.travelOrderAttachments']);

    });
    // End :: Dashboard route
    $routes->group('configuration',function($routes){
        $routes->get('/', [ConfigController::class, 'index'] , ['as' => 'view.configuration']);
        $routes->post('add-division', [ConfigController::class, 'addDivision'], ['as' => 'add.division']);
        $routes->post('add-unit', [ConfigController::class, 'addUnit'], ['as' => 'add.unit']);
    });
    $routes->group('user-management', function($routes){
        $routes->get('/', [UserManagementController::class, 'index'], ['as' => 'view.user-management']);
        $routes->post('data', [UserManagementController::class, 'dataUserManagement'], ['as' => 'data.userManagement']);
        $routes->get('details/(:num)', [UserManagementController::class, 'detailsUserManagement'], ['as' => 'details.userManagement']);
        $routes->post('update/(:num)', [UserManagementController::class, 'updateUserManagement/$1'], ['as' => 'update.userManagement']); 
        $routes->post('add',  [UserManagementController::class, 'registerUser'], ['as' => 'register.user']); 
    });
    $routes->group('profile', function($routes){
        $routes->get('/', [ProfileController::class, 'index'], ['as' => 'account-settings']);
        $routes->group('update', function ($routes){
        $routes->post('profile-picture', [ProfileController::class, 'updateProfilePicture'], ['as' => 'user.update-profile-picture']);
        $routes->post('change-password', [ProfileController::class, 'changePassword'], ['as' => 'user.change-password']);
        $routes->post('delete-account', [ProfileController::class, 'deleteAccount'], ['as' => 'user.delete-account']);
        $routes->post('first-name', [ProfileController::class, 'updateFirstName'], ['as' => 'update.firstname']);
        $routes->post('last-name', [ProfileController::class, 'updateLastName'], ['as' => 'update.lastname']);
        $routes->post('email', [ProfileController::class, 'updateEmail'], ['as' => 'update.email']);
        });
    });


    $routes->group('incoming-travel-orders', function($routes){
        $routes->get('/', [TravelOrderController::class, 'incomingTravelOrders'], ['as' => 'view.incomingTravelOrders']);
        $routes->post('data', [TravelOrderController::class, 'incomingTravelOrderData'], ['as' => 'data.incomingTravelOrders']);
        $routes->post('review/(:num)', [TravelOrderController::class, 'reviewTravelOrder/$1'], ['as' => 'review.travelOrder']);
    });

    $routes->group('processed-travel-orders', function($routes){
        $routes->get('/', [TravelOrderController::class, 'processedTravelOrders'], ['as' => 'view.processedTravelOrders']);
        $routes->post('data', [TravelOrderController::class, 'processedTravelOrderData'], ['as' => 'data.processedTravelOrders']);



        
});

});
