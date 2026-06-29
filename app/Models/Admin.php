<?php

namespace App\Models;

/**
 * Admin extends User
 *
 * INHERITANCE: mewarisi seluruh properti & method dari User.
 * POLYMORPHISM: meng-override getMenuAkses() dengan daftar menu khusus admin
 * (akses penuh termasuk manajemen kategori & akun petugas).
 */
class Admin extends User
{
    public function getMenuAkses(): array
    {
        return ['dashboard', 'kategori', 'kendaraan', 'customer', 'transaksi', 'user'];
    }
}
