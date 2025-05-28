<?php

namespace Core;

class Router
{
    private static array $routes = [];
    private static array $middleware = [];

    public static function get(string $uri, array|callable $action): void
    {
        self::addRoute('GET', $uri, $action);
    }

    public static function post(string $uri, array|callable $action): void
    {
        self::addRoute('POST', $uri, $action);
    }

    public static function put(string $uri, array|callable $action): void
    {
        self::addRoute('PUT', $uri, $action);
    }

    public static function delete(string $uri, array|callable $action): void
    {
        self::addRoute('DELETE', $uri, $action);
    }

    private static function addRoute(string $method, string $uri, array|callable $action): void
    {
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => self::$middleware
        ];
    }

    public static function middleware(array $middleware, callable $callback): void
    {
        self::$middleware = $middleware;
        $callback();
        self::$middleware = [];
    }

    public static function dispatch(string $uri, string $method): mixed
    {
        foreach (self::$routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                // Execute middleware
                foreach ($route['middleware'] as $middleware) {
                    $middlewareClass = match ($middleware) {
                        'auth' => \Core\Middleware\AuthMiddleware::class,
                        'guest' => \Core\Middleware\GuestMiddleware::class,
                        'csrf' => \Core\Middleware\CsrfMiddleware::class,
                        default => throw new \Exception("Middleware {$middleware} not found")
                    };

                    $middlewareInstance = new $middlewareClass();
                    $middlewareInstance->handle();
                }

                if (is_callable($route['action'])) {
                    return call_user_func($route['action']);
                }

                if (is_array($route['action'])) {
                    [$controller, $method] = $route['action'];
                    $controller = new $controller();
                    return $controller->$method();
                }
            }
        }

        throw new \Exception('Route not found');
    }
}
