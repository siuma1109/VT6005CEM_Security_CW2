<?php

namespace Core\Middleware;

use App\Services\CsrfService;

class CsrfMiddleware
{
    private CsrfService $csrfService;

    public function __construct()
    {
        $this->csrfService = new CsrfService();
    }

    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? null;

            if (!$token || !$this->csrfService->validateToken($token)) {
                http_response_code(403);
                echo json_encode(['error' => 'Invalid CSRF token']);
                exit();
            }
        }
    }
}
