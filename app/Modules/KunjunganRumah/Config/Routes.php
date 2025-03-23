<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('kunjunganrumah', ['namespace' => '\App\Modules\KunjunganRumah\Controllers'], static function ($routes) {
        $routes->get('/', 'KunjunganRumahController::index', ['as' => 'kunjunganrumah']);
        $routes->post('listdata', 'KunjunganRumahController::listData', ['as' => 'kunjunganrumah.listdata']);
        $routes->get('export', 'KunjunganRumahController::export', ['as' => 'kunjunganrumah.export']);
        $routes->post('done', 'KunjunganRumahController::done', ['as' => 'kunjunganrumah.done']);
        $routes->get('getdetail', 'KunjunganRumahController::getDetail', ['as' => 'kunjunganrumah.getdetail']);
        $routes->post('store', 'KunjunganRumahController::store', ['as' => 'kunjunganrumah.store']);
        $routes->get('getkunjunganrumah', 'KunjunganRumahController::getKunjunganRumah', ['as' => 'kunjunganrumah.getkunjunganrumah']);
        $routes->post('update', 'KunjunganRumahController::update', ['as' => 'kunjunganrumah.update']);
        $routes->get('delete', 'KunjunganRumahController::delete', ['as' => 'kunjunganrumah.delete']);
        $routes->post('getpdf', 'KunjunganRumahController::pdfSurat', ['as' => 'kunjunganrumah.getpdf']);
    });
});