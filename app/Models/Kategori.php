<?php

namespace App\Models;

use App\Core\Model;

/**
 * Kategori
 *
 * Representasi tabel kategori_kendaraan (mis. City Car, MPV, Matic, Sport, dst).
 * Tidak perlu menulis ulang method CRUD karena sudah diwarisi penuh dari Model (DRY).
 */
class Kategori extends Model
{
    protected function getTableName(): string
    {
        return 'kategori_kendaraan';
    }
}
