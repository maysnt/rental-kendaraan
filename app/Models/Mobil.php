<?php

namespace App\Models;

/**
 * Mobil extends Kendaraan
 *
 * INHERITANCE: mewarisi seluruh properti & method dari Kendaraan.
 * POLYMORPHISM:
 *  - hitungBiayaSewa() di-override: menambahkan biaya asuransi untuk sewa > 3 hari.
 *  - getInfoSpesifik() di-override: menampilkan kapasitas penumpang & transmisi.
 */
class Mobil extends Kendaraan
{
    private int $kapasitasPenumpang = 0;
    private string $transmisi = 'manual';

    public function setDataFromArray(array $row): static
    {
        parent::setDataFromArray($row);
        $this->kapasitasPenumpang = (int) ($row['kapasitas_penumpang'] ?? 0);
        $this->transmisi          = $row['transmisi'] ?? 'manual';

        return $this;
    }

    public function hitungBiayaSewa(int $jumlahHari): float
    {
        $biaya = parent::hitungBiayaSewa($jumlahHari);

        // Aturan khusus mobil: biaya asuransi tambahan untuk sewa lebih dari 3 hari
        if ($jumlahHari > 3) {
            $biaya += 50000;
        }

        return $biaya;
    }

    public function getInfoSpesifik(): string
    {
        return "Kapasitas {$this->kapasitasPenumpang} penumpang - Transmisi " . ucfirst($this->transmisi);
    }

    public function getKapasitasPenumpang(): int
    {
        return $this->kapasitasPenumpang;
    }

    public function getTransmisi(): string
    {
        return $this->transmisi;
    }
}
