<?php

namespace App\Models;

/**
 * Motor extends Kendaraan
 *
 * INHERITANCE: mewarisi seluruh properti & method dari Kendaraan.
 * POLYMORPHISM:
 *  - hitungBiayaSewa() di-override: diskon 10% untuk sewa 5 hari atau lebih.
 *  - getInfoSpesifik() di-override: menampilkan kapasitas cc & tipe motor.
 */
class Motor extends Kendaraan
{
    private int $kapasitasCc = 0;
    private string $tipeMotor = 'matic';

    public function setDataFromArray(array $row): static
    {
        parent::setDataFromArray($row);
        $this->kapasitasCc = (int) ($row['kapasitas_cc'] ?? 0);
        $this->tipeMotor   = $row['tipe_motor'] ?? 'matic';

        return $this;
    }

    public function hitungBiayaSewa(int $jumlahHari): float
    {
        $biaya = parent::hitungBiayaSewa($jumlahHari);

        // Aturan khusus motor: diskon 10% untuk sewa 5 hari atau lebih
        if ($jumlahHari >= 5) {
            $biaya *= 0.9;
        }

        return $biaya;
    }

    public function getInfoSpesifik(): string
    {
        return "{$this->kapasitasCc}cc - Tipe " . ucfirst($this->tipeMotor);
    }

    public function getKapasitasCc(): int
    {
        return $this->kapasitasCc;
    }

    public function getTipeMotor(): string
    {
        return $this->tipeMotor;
    }
}
