<?php

namespace Core\Middleware;

use App\Services\DatabaseSessionService;

class GuestMiddleware
{
    public function handle()
    {
        if (DatabaseSessionService::getUser()) {
            header('Location: /');
            exit();
        }
    }
}
