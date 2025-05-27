<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/helpers.php';

use Core\Router;

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Get the current URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    // Dispatch the route
    $response = Router::dispatch($uri, $method);
    echo $response;
} catch (\Exception $e) {
    echo $e->getMessage();
    // Handle 404 or other errors
    http_response_code(404);
    echo '404 Not Found';
}

session_write_close();