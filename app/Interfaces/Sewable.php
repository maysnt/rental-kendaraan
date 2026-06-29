<?php

namespace App\Interfaces;

/**
 * Kontrak untuk objek kendaraan yang dapat disewakan.
 * Setiap jenis kendaraan (Mobil, Motor) WAJIB menyediakan cara
 * menghitung biaya sewa dan info spesifiknya sendiri (lihat POLYMORPHISM
 * pada App\Models\Mobil dan App\Models\Motor).
 */
interface Sewable
{
    public function hitungBiayaSewa(int $jumlahHari): float;

    public function getInfoSpesifik(): string;
}
