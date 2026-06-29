<?php

use App\Helpers\Helper;
use App\Core\Session;

$menuAkses    = Session::get('menu_akses', []);
$currentRoute = $_GET['route'] ?? 'dashboard';
?>
<aside class="rk-sidebar rk-no-print">
    <div class="rk-brand">
        <div class="rk-brand-mark">RK</div>
        <div class="rk-brand-text">
            Rental Kendaraan
            <small>Sistem Manajemen Sewa</small>
        </div>
    </div>

    <div class="rk-nav-section">Menu Utama</div>
    <?php if (in_array('dashboard', $menuAkses, true)): ?>
        <a href="<?= Helper::url('dashboard') ?>" class="rk-nav-link <?= Helper::isActiveRoute('dashboard', $currentRoute) ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge rk-nav-icon"></i> Dashboard
        </a>
    <?php endif; ?>

    <div class="rk-nav-section">Data Master</div>
    <?php if (in_array('kategori', $menuAkses, true)): ?>
        <a href="<?= Helper::url('kategori') ?>" class="rk-nav-link <?= Helper::isActiveRoute('kategori', $currentRoute) ? 'active' : '' ?>">
            <i class="fa-solid fa-tags rk-nav-icon"></i> Kategori Kendaraan
        </a>
    <?php endif; ?>
    <?php if (in_array('kendaraan', $menuAkses, true)): ?>
        <a href="<?= Helper::url('kendaraan') ?>" class="rk-nav-link <?= Helper::isActiveRoute('kendaraan', $currentRoute) ? 'active' : '' ?>">
            <i class="fa-solid fa-car-side rk-nav-icon"></i> Kendaraan
        </a>
    <?php endif; ?>
    <?php if (in_array('customer', $menuAkses, true)): ?>
        <a href="<?= Helper::url('customer') ?>" class="rk-nav-link <?= Helper::isActiveRoute('customer', $currentRoute) ? 'active' : '' ?>">
            <i class="fa-solid fa-users rk-nav-icon"></i> Pelanggan
        </a>
    <?php endif; ?>

    <div class="rk-nav-section">Transaksi</div>
    <?php if (in_array('transaksi', $menuAkses, true)): ?>
        <a href="<?= Helper::url('transaksi') ?>" class="rk-nav-link <?= Helper::isActiveRoute('transaksi', $currentRoute) ? 'active' : '' ?>">
            <i class="fa-solid fa-receipt rk-nav-icon"></i> Transaksi Sewa
        </a>
    <?php endif; ?>

    <?php if (in_array('user', $menuAkses, true)): ?>
        <div class="rk-nav-section">Administrasi</div>
        <a href="<?= Helper::url('user') ?>" class="rk-nav-link <?= Helper::isActiveRoute('user', $currentRoute) ? 'active' : '' ?>">
            <i class="fa-solid fa-user-shield rk-nav-icon"></i> Akun Pengguna
        </a>
    <?php endif; ?>
</aside>
