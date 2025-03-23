<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('grafik', ['namespace' => '\App\Modules\Grafik\Controllers'], static function ($routes) {
        $routes->get('grafik', 'GrafikController::index', ['as' => 'grafik']);
        $routes->post('grafik/listdataall', 'GrafikController::listDataAll', ['as' => 'grafik.listdataall']);
    });
});
