<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->get('logout', '\App\Modules\Auth\Controllers\AuthController::logoutHandler', ['as' => 'logout']);
});

$routes->group('', ['filter' => 'cifilter:guest'], static function ($routes) {
    $routes->get('login', '\App\Modules\Auth\Controllers\AuthController::loginForm', ['as' => 'login.form']);
    $routes->post('login', '\App\Modules\Auth\Controllers\AuthController::loginHandler', ['as' => 'login.handler']);
    $routes->get('forgot-password', '\App\Modules\Auth\Controllers\AuthController::forgotForm', ['as' => 'forgot.form']);
    $routes->post('send-password-reset-link', '\App\Modules\Auth\Controllers\AuthController::sendPasswordResetLink', ['as' => 'send_password_reset_link']);
    $routes->get('password/reset/(:any)', '\App\Modules\Auth\Controllers\AuthController::resetPassword/$1', ['as' => 'reset-password']);
    $routes->post('reset-password-handler/(:any)', '\App\Modules\Auth\Controllers\AuthController::resetPasswordHandler/$1', ['as' => 'reset-password-handler']);
});