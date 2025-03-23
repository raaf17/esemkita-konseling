<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('materi', ['namespace' => '\App\Modules\Materi\Controllers'], static function ($routes) {
        $routes->get('/', 'MateriController::index', ['as' => 'materi']);
        $routes->post('listdata', 'MateriController::listData', ['as' => 'materi.listdata']);
        $routes->post('store', 'MateriController::store', ['as' => 'materi.store']);
        $routes->get('getmateri', 'MateriController::getMateri', ['as' => 'materi.getmateri']);
        $routes->post('update', 'MateriController::update', ['as' => 'materi.update']);
        $routes->get('delete', 'MateriController::delete', ['as' => 'materi.delete']);
        $routes->get('export', 'MateriController::export', ['as' => 'materi.export']);
        $routes->post('import', 'MateriController::import', ['as' => 'materi.import']);
        $routes->post('multipledelete', 'MateriController::multipleDelete', ['as' => 'materi.multipledelete']);
    });
});