<?php

/**
 * Autoloader sederhana (tanpa Composer) untuk namespace "App".
 * Memetakan App\Foo\Bar ke file app/Foo/Bar.php
 */
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return; // bukan class milik aplikasi ini, biarkan autoloader lain menangani
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
