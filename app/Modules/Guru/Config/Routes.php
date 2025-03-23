<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('guru', ['namespace' => '\App\Modules\Guru\Controllers'], static function ($routes) {
        $routes->get('/', 'GuruController::index', ['as' => 'guru']);
        $routes->post('listdata', 'GuruController::listData', ['as' => 'guru.listdata']);
        $routes->post('store', 'GuruController::store', ['as' => 'guru.store']);
        $routes->get('getguru', 'GuruController::getGuru', ['as' => 'guru.getguru']);
        $routes->post('update', 'GuruController::update', ['as' => 'guru.update']);
        $routes->get('delete', 'GuruController::delete', ['as' => 'guru.delete']);
        $routes->get('export', 'GuruController::export', ['as' => 'guru.export']);
        $routes->post('import', 'GuruController::import', ['as' => 'guru.import']);
        $routes->get('getdetail', 'GuruController::getDetail', ['as' => 'guru.getdetail']);
        $routes->post('multipledelete', 'GuruController::multipleDelete', ['as' => 'guru.multipledelete']);
    });
});