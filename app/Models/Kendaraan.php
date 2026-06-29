<?php

namespace App\Models;

use App\Core\Model;
use App\Interfaces\Sewable;

/**
 * Kendaraan (abstract)
 *
 * INHERITANCE: induk dari Mobil dan Motor.
 * ABSTRACTION: implements interface Sewable + punya method abstract getInfoSpesifik().
 * ENCAPSULATION: seluruh properti protected, hanya bisa dibaca lewat getter.
 *
 * Catatan desain: Mobil & Motor disimpan dalam SATU tabel "kendaraan" (single table
 * inheritance) yang dibedakan oleh kolom "jenis". Karena class ini abstract, untuk
 * operasi CRUD generik pada tabel (getAll/create/update/delete yang diwarisi dari Model)
 * controller cukup membuat instance salah satu turunannya, lihat KendaraanFactory.
 */
abstract class Kendaraan extends Model implements Sewable
{
    protected int $id = 0;
    protected int $kategoriId = 0;
    protected string $kodeKendaraan = '';
    protected string $merk = '';
    protected string $model = '';
    protected int $tahun = 0;
    protected string $platNomor = '';
    protected float $hargaSewaHarian = 0.0;
    protected string $status = 'tersedia';
    protected ?string $foto = null;
    protected string $deskripsi = '';

    protected function getTableName(): string
    {
        return 'kendaraan';
    }

    public function setDataFromArray(array $row): static
    {
        $this->id              = (int) ($row['id'] ?? 0);
        $this->kategoriId      = (int) ($row['kategori_id'] ?? 0);
        $this->kodeKendaraan   = $row['kode_kendaraan'] ?? '';
        $this->merk            = $row['merk'] ?? '';
        $this->model           = $row['model'] ?? '';
        $this->tahun           = (int) ($row['tahun'] ?? 0);
        $this->platNomor       = $row['plat_nomor'] ?? '';
        $this->hargaSewaHarian = (float) ($row['harga_sewa_harian'] ?? 0);
        $this->status          = $row['status'] ?? 'tersedia';
        $this->foto            = $row['foto'] ?? null;
        $this->deskripsi       = $row['deskripsi'] ?? '';

        return $this;
    }

    /**
     * Implementasi dasar perhitungan biaya sewa (harga harian x jumlah hari).
     * POLYMORPHISM: Mobil dan Motor meng-override method ini untuk menambahkan
     * aturan biaya tambahan/diskon yang berbeda satu sama lain.
     */
    public function hitungBiayaSewa(int $jumlahHari): float
    {
        $jumlahHari = max($jumlahHari, 1);
        return $this->hargaSewaHarian * $jumlahHari;
    }

    /**
     * ABSTRACTION: setiap jenis kendaraan WAJIB punya cara sendiri
     * menampilkan info spesifiknya (kapasitas penumpang vs cc motor, dst).
     */
    abstract public function getInfoSpesifik(): string;

    public function getId(): int
    {
        return $this->id;
    }

    public function getKategoriId(): int
    {
        return $this->kategoriId;
    }

    public function getKodeKendaraan(): string
    {
        return $this->kodeKendaraan;
    }

    public function getMerk(): string
    {
        return $this->merk;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getNamaLengkap(): string
    {
        return trim("{$this->merk} {$this->model} ({$this->tahun})");
    }

    public function getTahun(): int
    {
        return $this->tahun;
    }

    public function getPlatNomor(): string
    {
        return $this->platNomor;
    }

    public function getHargaSewaHarian(): float
    {
        return $this->hargaSewaHarian;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function getDeskripsi(): string
    {
        return $this->deskripsi;
    }
}
