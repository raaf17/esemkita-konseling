<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('loginactivity', ['namespace' => '\App\Modules\LoginActivity\Controllers'], static function ($routes) {
        $routes->get('/', 'LoginActivityController::index', ['as' => 'loginactivity']);
        $routes->post('listdata', 'LoginActivityController::listData', ['as' => 'loginactivity.listdata']);
        $routes->get('delete', 'LoginActivityController::delete', ['as' => 'loginactivity.delete']);
        $routes->post('multipledelete', 'LoginActivityController::multipleDelete', ['as' => 'loginactivity.multipledelete']);
    });
});