<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('laporan', ['namespace' => '\App\Modules\Laporan\Controllers'], static function ($routes) {
        $routes->get('/', 'LaporanController::index', ['as' => 'laporan']);
        $routes->post('listdata', 'LaporanController::listData', ['as' => 'laporan.listdata']);
        $routes->post('store', 'LaporanController::store', ['as' => 'laporan.store']);
        $routes->get('delete', 'LaporanController::delete', ['as' => 'laporan.delete']);
        $routes->get('export', 'LaporanController::export', ['as' => 'laporan.export']);
        $routes->post('approve', 'LaporanController::approve', ['as' => 'laporan.approve']);
        $routes->post('unapprove', 'LaporanController::unapprove', ['as' => 'laporan.unapprove']);
        $routes->get('getdetail', 'LaporanController::getDetail', ['as' => 'laporan.getdetail']);
    });
});