<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database
 *
 * Menerapkan pola Singleton agar hanya ada SATU koneksi PDO yang dipakai
 * bersama oleh seluruh aplikasi.
 *
 * - ENCAPSULATION: properti $connection bersifat private, constructor private.
 * - STATIC METHOD: getInstance() adalah static method (salah satu wajib di requirement).
 * - EXCEPTION HANDLING: percobaan koneksi dibungkus try-catch.
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    /**
     * Constructor bersifat private supaya class ini tidak bisa di-instantiate
     * langsung dari luar (memaksa pemakaian Database::getInstance()).
     */
    private function __construct()
    {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // Tampilkan pesan yang ramah, jangan expose detail sensitif di production
            die(
                '<h2>Koneksi database gagal</h2>' .
                '<p>Pastikan MySQL aktif dan konfigurasi pada config/config.php sudah benar.</p>' .
                '<p><small>' . htmlspecialchars($e->getMessage()) . '</small></p>'
            );
        }
    }

    /**
     * Static method untuk mengambil satu-satunya instance Database (Singleton).
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Cegah cloning agar singleton tetap unik.
     */
    private function __clone(): void
    {
    }
}
