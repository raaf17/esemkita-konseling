<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('dashboard', ['namespace' => '\App\Modules\Dashboard\Controllers'], static function ($routes) {
        $routes->get('/', 'DashboardController::index', ['as' => 'dashboard']);
        $routes->post('dashboard/listdataall', 'DashboardController::listDataAll', ['as' => 'dashboard.listdataall']);
    });
});
