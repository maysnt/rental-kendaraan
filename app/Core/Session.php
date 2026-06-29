<?php

namespace App\Core;

use App\Helpers\Helper;
use App\Models\User;

/**
 * Session
 *
 * Membungkus seluruh akses ke superglobal $_SESSION supaya logic Authentication
 * (login, logout, cek role) terpusat di satu tempat (ENCAPSULATION).
 */
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(User $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id']    = $user->getId();
        $_SESSION['username']   = $user->getUsername();
        $_SESSION['nama']       = $user->getNama();
        $_SESSION['role']       = $user->getRole();
        $_SESSION['menu_akses'] = $user->getMenuAkses();
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie('PHPSESSID', '', time() - 42000, $params['path'], $params['domain']);
        }

        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . Helper::url('login'));
            exit;
        }
    }

    /**
     * Guard berbasis role, dipakai misalnya di UserController (khusus admin).
     *
     * @param string[] $roles
     */
    public static function requireRole(array $roles): void
    {
        self::requireLogin();

        if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
            http_response_code(403);
            die('<h2>403 - Akses Ditolak</h2><p>Anda tidak memiliki izin untuk membuka halaman ini.</p>');
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    public static function getFlash(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }
}
