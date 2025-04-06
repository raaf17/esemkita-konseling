<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('home', ['namespace' => '\App\Client\Home\Controllers'], static function ($routes) {
        $routes->get('/', 'HomeController::index', ['as' => 'home']);
    });
});
