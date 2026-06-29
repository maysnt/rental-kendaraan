<?php

namespace App\Models;

/**
 * KendaraanFactory
 *
 * Factory Pattern sederhana: menentukan apakah sebuah baris data kendaraan
 * harus diwujudkan sebagai objek Mobil atau Motor, berdasarkan kolom "jenis".
 * Inilah titik di mana POLYMORPHISM benar-benar terasa manfaatnya: kode pemanggil
 * (Controller/View) tidak perlu tahu/peduli jenis konkretnya, cukup panggil
 * $kendaraan->hitungBiayaSewa() atau $kendaraan->getInfoSpesifik().
 *
 * STATIC METHOD: buat() & buatBanyak() dipanggil tanpa instantiate Factory.
 */
class KendaraanFactory
{
    public static function buat(array $row): Kendaraan
    {
        $jenis = $row['jenis'] ?? 'mobil';

        $kendaraan = match ($jenis) {
            'motor' => new Motor(),
            default => new Mobil(),
        };

        return $kendaraan->setDataFromArray($row);
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return Kendaraan[]
     */
    public static function buatBanyak(array $rows): array
    {
        return array_map(static fn (array $row) => self::buat($row), $rows);
    }
}
