<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('users', ['namespace' => '\App\Modules\Users\Controllers'], static function ($routes) {
        $routes->get('/', 'UserController::index', ['as' => 'users']);
        $routes->post('listdata', 'UserController::listData', ['as' => 'users.listdata']);
        $routes->post('store', 'UserController::store', ['as' => 'users.store']);
        $routes->get('getusers', 'UserController::getUsers', ['as' => 'users.getusers']);
        $routes->post('update', 'UserController::update', ['as' => 'users.update']);
        $routes->get('delete', 'UserController::delete', ['as' => 'users.delete']);
        $routes->get('export', 'UserController::export', ['as' => 'users.export']);
        $routes->post('import', 'UserController::import', ['as' => 'users.import']);
        $routes->post('multipledelete', 'UserController::multipleDelete', ['as' => 'users.multipledelete']);
        $routes->get('comboboxrole', 'UserController::comboboxRole', ['as' => 'users.comboboxrole']);
    });
});
