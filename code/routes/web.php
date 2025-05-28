<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Services\DatabaseSessionService;
use Core\Router;

Router::get('/', function () {
    $message = DatabaseSessionService::get('message');
    return view('main_page', [
        'content' => view('home', [
            'message' => $message
        ])
    ]);
});

// Guest routes (login/register)
Router::middleware(['guest'], function () {
    Router::get('/register', [AuthController::class, 'register']);
    Router::post('/register', [AuthController::class, 'registerPost']);

    Router::get('/login', [AuthController::class, 'login']);
    Router::post('/login', [AuthController::class, 'loginPost']);

    Router::get('/mfa', [AuthController::class, 'mfa']);
    Router::post('/mfa', [AuthController::class, 'mfaPost']);
});

// Protected routes
Router::middleware(['auth'], function () {
    Router::get('/logout', [AuthController::class, 'logout']);

    Router::get('/make_appointment', [AppointmentController::class, 'makeAppointment']);
    Router::post('/make_appointment', [AppointmentController::class, 'makeAppointmentPost']);
    Router::get('/appointments', [AppointmentController::class, 'appointments']);
});
