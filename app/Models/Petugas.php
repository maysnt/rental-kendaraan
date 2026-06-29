<?php

namespace App\Models;

/**
 * Petugas extends User
 *
 * INHERITANCE: mewarisi seluruh properti & method dari User.
 * POLYMORPHISM: meng-override getMenuAkses() dengan daftar menu yang lebih terbatas
 * (tidak ada akses ke manajemen kategori & akun pengguna, khusus admin).
 */
class Petugas extends User
{
    public function getMenuAkses(): array
    {
        return ['dashboard', 'kendaraan', 'customer', 'transaksi'];
    }
}
