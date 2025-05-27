<?php

use App\Http\Controllers\AuthController;
use Core\Router;

Router::get('/', function () {
    return view('main_page', [
        'content' => view('home')
    ]);
});

// Guest routes (login/register)
Router::middleware(['guest'], function () {
    Router::get('/register', [AuthController::class, 'register']);
    Router::post('/register', [AuthController::class, 'registerPost']);

    Router::get('/login', [AuthController::class, 'login']);
    Router::post('/login', [AuthController::class, 'loginPost']);
});

// Protected routes
Router::middleware(['auth'], function () {
    Router::get('/logout', [AuthController::class, 'logout']);
});
