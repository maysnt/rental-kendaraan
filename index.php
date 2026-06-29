<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\KategoriController;
use App\Controllers\KendaraanController;
use App\Controllers\TransaksiController;
use App\Controllers\UserController;
use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

// ---- Authentication ----
$router->add('login', [AuthController::class, 'loginForm']);
$router->add('logout', [AuthController::class, 'logout']);

// ---- Dashboard ----
$router->add('dashboard', [DashboardController::class, 'index']);

// ---- Kategori Kendaraan ----
$router->add('kategori', [KategoriController::class, 'index']);
$router->add('kategori/create', [KategoriController::class, 'create']);
$router->add('kategori/edit', [KategoriController::class, 'edit']);
$router->add('kategori/delete', [KategoriController::class, 'delete']);

// ---- Kendaraan ----
$router->add('kendaraan', [KendaraanController::class, 'index']);
$router->add('kendaraan/create', [KendaraanController::class, 'create']);
$router->add('kendaraan/edit', [KendaraanController::class, 'edit']);
$router->add('kendaraan/delete', [KendaraanController::class, 'delete']);
$router->add('kendaraan/detail', [KendaraanController::class, 'detail']);

// ---- Customer ----
$router->add('customer', [CustomerController::class, 'index']);
$router->add('customer/create', [CustomerController::class, 'create']);
$router->add('customer/edit', [CustomerController::class, 'edit']);
$router->add('customer/delete', [CustomerController::class, 'delete']);
$router->add('customer/detail', [CustomerController::class, 'detail']);

// ---- Transaksi Sewa ----
$router->add('transaksi', [TransaksiController::class, 'index']);
$router->add('transaksi/create', [TransaksiController::class, 'create']);
$router->add('transaksi/edit', [TransaksiController::class, 'edit']);
$router->add('transaksi/delete', [TransaksiController::class, 'delete']);
$router->add('transaksi/detail', [TransaksiController::class, 'detail']);
$router->add('transaksi/cetak', [TransaksiController::class, 'cetak']);
$router->add('transaksi/selesai', [TransaksiController::class, 'selesai']);
$router->add('transaksi/batal', [TransaksiController::class, 'batal']);

// ---- User / Akun Petugas (khusus admin) ----
$router->add('user', [UserController::class, 'index']);
$router->add('user/create', [UserController::class, 'create']);
$router->add('user/edit', [UserController::class, 'edit']);
$router->add('user/delete', [UserController::class, 'delete']);

$route = $_GET['route'] ?? (Session::isLoggedIn() ? 'dashboard' : 'login');
$router->dispatch($route);
