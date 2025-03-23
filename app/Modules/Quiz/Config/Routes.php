<?php

$routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
    $routes->group('quiz', ['namespace' => '\App\Modules\Quiz\Controllers'], static function ($routes) {
        $routes->get('/', 'QuizController::index', ['as' => 'quiz']);
        $routes->post('listdata', 'QuizController::listData', ['as' => 'quiz.listdata']);
        $routes->post('store', 'QuizController::store', ['as' => 'quiz.store']);
        $routes->get('getquiz', 'QuizController::getQuiz', ['as' => 'quiz.getquiz']);
        $routes->post('update', 'QuizController::update', ['as' => 'quiz.update']);
        $routes->get('delete', 'QuizController::delete', ['as' => 'quiz.delete']);
        $routes->get('export', 'QuizController::export', ['as' => 'quiz.export']);
        $routes->post('import', 'QuizController::import', ['as' => 'quiz.import']);
        $routes->post('multipledelete', 'QuizController::multipleDelete', ['as' => 'quiz.multipledelete']);
    });
});