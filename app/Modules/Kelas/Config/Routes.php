<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('kelas', ['namespace' => '\App\Modules\Kelas\Controllers'], static function ($routes) {
        $routes->get('/', 'KelasController::index', ['as' => 'kelas']);
        $routes->post('listdata', 'KelasController::listData', ['as' => 'kelas.listdata']);
        $routes->post('store', 'KelasController::store', ['as' => 'kelas.store']);
        $routes->get('getkelas', 'KelasController::getKelas', ['as' => 'kelas.getkelas']);
        $routes->post('update', 'KelasController::update', ['as' => 'kelas.update']);
        $routes->get('delete', 'KelasController::delete', ['as' => 'kelas.delete']);
        $routes->get('export', 'KelasController::export', ['as' => 'kelas.export']);
        $routes->post('import', 'KelasController::import', ['as' => 'kelas.import']);
        $routes->post('multipledelete', 'KelasController::multipleDelete', ['as' => 'kelas.multipledelete']);
    });
});