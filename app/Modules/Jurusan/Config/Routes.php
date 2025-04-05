<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('jurusan', ['namespace' => '\App\Modules\Jurusan\Controllers'], static function ($routes) {
        $routes->get('/', 'JurusanController::index', ['as' => 'jurusan']);
        $routes->post('listdata', 'JurusanController::listData', ['as' => 'jurusan.listdata']);
        $routes->post('store', 'JurusanController::store', ['as' => 'jurusan.store']);
        $routes->get('getjurusan', 'JurusanController::getJurusan', ['as' => 'jurusan.getjurusan']);
        $routes->post('update', 'JurusanController::update', ['as' => 'jurusan.update']);
        $routes->get('delete', 'JurusanController::delete', ['as' => 'jurusan.delete']);
        $routes->get('export', 'JurusanController::export', ['as' => 'jurusan.export']);
        $routes->post('import', 'JurusanController::import', ['as' => 'jurusan.import']);
        $routes->post('multipledelete', 'JurusanController::multipleDelete', ['as' => 'jurusan.multipledelete']);
        $routes->get('comboboxguru', 'JurusanController::comboboxGuru', ['as' => 'jurusan.comboboxguru']);
    });
});