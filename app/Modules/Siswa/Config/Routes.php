<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('siswa', ['namespace' => '\App\Modules\Siswa\Controllers'], static function ($routes) {
        $routes->get('/', 'SiswaController::index', ['as' => 'siswa']);
        $routes->post('listdata', 'SiswaController::listData', ['as' => 'siswa.listdata']);
        $routes->post('store', 'SiswaController::store', ['as' => 'siswa.store']);
        $routes->get('getsiswa', 'SiswaController::getSiswa', ['as' => 'siswa.getsiswa']);
        $routes->post('update', 'SiswaController::update', ['as' => 'siswa.update']);
        $routes->get('delete', 'SiswaController::delete', ['as' => 'siswa.delete']);
        $routes->get('export', 'SiswaController::export', ['as' => 'siswa.export']);
        $routes->post('import', 'SiswaController::import', ['as' => 'siswa.import']);
        $routes->get('getdetail', 'SiswaController::getDetail', ['as' => 'siswa.getdetail']);
        $routes->post('multipledelete', 'SiswaController::multipleDelete', ['as' => 'siswa.multipledelete']);
    });
});