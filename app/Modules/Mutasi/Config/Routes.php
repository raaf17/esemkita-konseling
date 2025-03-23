<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('mutasi', ['namespace' => '\App\Modules\Mutasi\Controllers'], static function ($routes) {
        $routes->get('/', 'MutasiController::index', ['as' => 'mutasi']);
        $routes->post('listdata', 'MutasiController::listData', ['as' => 'mutasi.listdata']);
        $routes->post('store', 'MutasiController::store', ['as' => 'mutasi.store']);
        $routes->get('getmutasi', 'MutasiController::getMutasi', ['as' => 'mutasi.getmutasi']);
        $routes->post('update', 'MutasiController::update', ['as' => 'mutasi.update']);
        $routes->get('delete', 'MutasiController::delete', ['as' => 'mutasi.delete']);
        $routes->get('export', 'MutasiController::export', ['as' => 'mutasi.export']);
        $routes->post('multipledelete', 'MutasiController::multipleDelete', ['as' => 'mutasi.multipledelete']);
    });
});