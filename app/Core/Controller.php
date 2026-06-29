<?php

namespace App\Core;

use App\Helpers\Helper;

/**
 * Controller (abstract)
 *
 * Base class untuk seluruh Controller pada pola MVC sederhana ini.
 * ABSTRACTION: class ini abstract, tidak pernah di-instantiate langsung,
 * hanya dipakai sebagai induk (lihat AuthController, DashboardController, dst).
 */
abstract class Controller
{
    /**
     * Merender file view dan otomatis menyediakan variabel ke dalamnya.
     */
    protected function view(string $viewPath, array $data = []): void
    {
        extract($data);
        $viewFile = dirname(__DIR__, 2) . '/views/' . $viewPath . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            die("View tidak ditemukan: {$viewPath}");
        }

        require $viewFile;
    }

    protected function redirect(string $route, array $params = []): void
    {
        header('Location: ' . Helper::url($route, $params));
        exit;
    }

    /**
     * Mengambil input dari POST lalu GET (fallback), dengan default value.
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }
        if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        }
        return $default;
    }

    protected function setFlash(string $type, string $message): void
    {
        Session::setFlash($type, $message);
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }
}
