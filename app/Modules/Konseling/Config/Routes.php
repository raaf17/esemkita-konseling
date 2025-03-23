<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('konseling', ['namespace' => '\App\Modules\Konseling\Controllers'], static function ($routes) {
        $routes->get('/', 'KonselingController::index', ['as' => 'konseling']);
        $routes->post('listdata', 'KonselingController::listData', ['as' => 'konseling.listdata']);
        $routes->post('store', 'KonselingController::store', ['as' => 'konseling.store']);
        $routes->get('delete', 'KonselingController::delete', ['as' => 'konseling.delete']);
        $routes->get('export', 'KonselingController::export', ['as' => 'konseling.export']);
        $routes->post('approve', 'KonselingController::approve', ['as' => 'konseling.approve']);
        $routes->post('unapprove', 'KonselingController::unapprove', ['as' => 'konseling.unapprove']);
        $routes->get('getdetail', 'KonselingController::getDetail', ['as' => 'konseling.getdetail']);
    });
});