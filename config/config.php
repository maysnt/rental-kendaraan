<?php

/**
 * Konfigurasi aplikasi.
 * Sesuaikan DB_HOST, DB_NAME, DB_USER, DB_PASS dengan environment Anda.
 */

// ---- Konfigurasi Database ----
define('DB_HOST', 'localhost');
define('DB_NAME', 'rental_kendaraan');
define('DB_USER', 'root');
define('DB_PASS', '');

// ---- Konfigurasi URL ----
// Kosongkan jika menjalankan dengan: php -S localhost:8000 -t public
// Isi dengan path subfolder jika di-deploy di XAMPP/Laragon, misalnya '/rental-kendaraan/public'
define('BASE_URL', '/rental-kendaraan/public');
// ---- Konfigurasi Upload File ----
define('UPLOAD_PATH', __DIR__ . '/../public/assets/uploads/kendaraan/');
define('UPLOAD_URL', 'assets/uploads/kendaraan/');

// ---- Zona waktu ----
date_default_timezone_set('Asia/Jakarta');

// ---- Error reporting (matikan display_errors di production) ----
error_reporting(E_ALL);
ini_set('display_errors', '1');
