<?php

namespace Core\Middleware;

use App\Services\DatabaseSessionService;

class GuestMiddleware
{
    public function handle()
    {
        if (DatabaseSessionService::get('user_id')) {
            header('Location: /');
            exit();
        }
    }
}
