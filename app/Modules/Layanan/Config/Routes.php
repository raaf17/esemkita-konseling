<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('layanan', ['namespace' => '\App\Modules\Layanan\Controllers'], static function ($routes) {
        $routes->get('/', 'LayananController::index', ['as' => 'layanan']);
        $routes->post('listdata', 'LayananController::listData', ['as' => 'layanan.listdata']);
        $routes->post('store', 'LayananController::store', ['as' => 'layanan.store']);
        $routes->get('getlayanan', 'LayananController::getLayanan', ['as' => 'layanan.getlayanan']);
        $routes->post('update', 'LayananController::update', ['as' => 'layanan.update']);
        $routes->get('delete', 'LayananController::delete', ['as' => 'layanan.delete']);
        $routes->get('export', 'LayananController::export', ['as' => 'layanan.export']);
        $routes->post('import', 'LayananController::import', ['as' => 'layanan.import']);
        $routes->post('multipledelete', 'LayananController::multipleDelete', ['as' => 'layanan.multipledelete']);
    });
});