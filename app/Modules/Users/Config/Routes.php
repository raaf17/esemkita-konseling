<?php

$routes->group('guru', static function ($routes) {
    $routes->get('/', '\App\Modules\Controllers\GuruController::index', ['as' => 'guru']);
    $routes->post('listdata', '\App\Modules\Controllers\GuruController::listData', ['as' => 'guru.listdata']);
    $routes->post('store', '\App\Modules\Controllers\GuruController::store', ['as' => 'guru.store']);
    $routes->get('getguru', '\App\Modules\Controllers\GuruController::getGuru', ['as' => 'guru.getguru']);
    $routes->post('update', '\App\Modules\Controllers\GuruController::update', ['as' => 'guru.update']);
    $routes->get('delete', '\App\Modules\Controllers\GuruController::delete', ['as' => 'guru.delete']);
    $routes->get('export', '\App\Modules\Controllers\GuruController::export', ['as' => 'guru.export']);
    $routes->post('import', '\App\Modules\Controllers\GuruController::import', ['as' => 'guru.import']);
    $routes->get('getdetail', '\App\Modules\Controllers\GuruController::getDetail', ['as' => 'guru.getdetail']);
    $routes->post('multipledelete', '\App\Modules\Controllers\GuruController::multipleDelete', ['as' => 'guru.multipledelete']);
});