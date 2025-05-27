<?php

namespace Core\Middleware;

use App\Services\DatabaseSessionService;

class AuthMiddleware
{
    public function handle()
    {
        if (!DatabaseSessionService::getUser()) {
            header('Location: /login');
            exit();
        }
    }
}
