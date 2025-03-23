<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('hakakses', ['namespace' => '\App\Modules\HakAkses\Controllers'], static function ($routes) {
        $routes->get('/', 'HakAksesController::index', ['as' => 'hakakses']);
        $routes->post('listdata', 'HakAksesController::listData', ['as' => 'hakakses.listdata']);
        $routes->post('edithakakses', 'HakAksesController::editHakAkses', ['as' => 'hakakses.edithakakses']);
        $routes->post('simpanhakakses', 'HakAksesController::simpanHakAkses', ['as' => 'hakakses.simpanhakakses']);
        $routes->post('store', 'HakAksesController::store', ['as' => 'hakakses.store']);
        $routes->get('getrole', 'HakAksesController::getRole', ['as' => 'hakakses.getrole']);
        $routes->post('update', 'HakAksesController::update', ['as' => 'hakakses.update']);
        $routes->get('delete', 'HakAksesController::delete', ['as' => 'hakakses.delete']);
        $routes->get('export', 'HakAksesController::export', ['as' => 'hakakses.export']);
        $routes->post('multipledelete', 'HakAksesController::multipleDelete', ['as' => 'hakakses.multipledelete']);
    });
});