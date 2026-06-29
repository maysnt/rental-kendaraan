<?php

namespace App\Models;

use App\Core\Model;

/**
 * Customer
 *
 * Representasi tabel customers (data pelanggan/penyewa).
 * CRUD generik (create, getAll, getById, update, delete, search, pagination)
 * sudah tersedia lewat parent::Model.
 */
class Customer extends Model
{
    protected function getTableName(): string
    {
        return 'customers';
    }
}
