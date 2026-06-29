<?php

namespace App\Core;

/**
 * Router
 *
 * Memetakan string route (?route=...) ke pasangan [ControllerClass, method].
 * EXCEPTION HANDLING: dispatch() membungkus seluruh proses dengan try-catch
 * sebagai pengaman terakhir agar aplikasi tidak menampilkan fatal error mentah ke user.
 */
class Router
{
    /** @var array<string, array{0: class-string, 1: string}> */
    private array $routes = [];

    public function add(string $route, array $handler): void
    {
        $this->routes[$route] = $handler;
    }

    public function dispatch(string $route): void
    {
        if (!array_key_exists($route, $this->routes)) {
            http_response_code(404);
            require dirname(__DIR__, 2) . '/views/errors/404.php';
            return;
        }

        [$controllerClass, $method] = $this->routes[$route];

        try {
            $controller = new $controllerClass();

            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} tidak ditemukan pada {$controllerClass}.");
            }

            $controller->$method();
        } catch (\Throwable $e) {
            http_response_code(500);
            $errorMessage = $e->getMessage();
            require dirname(__DIR__, 2) . '/views/errors/500.php';
        }
    }
}
