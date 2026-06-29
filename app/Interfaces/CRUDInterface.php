<?php

namespace App\Interfaces;

/**
 * Kontrak operasi CRUD standar.
 * Bagian dari penerapan ABSTRACTION (interface) yang diwajibkan di requirement OOP.
 * Diimplementasikan oleh App\Core\Model dan diturunkan ke seluruh Model anak.
 */
interface CRUDInterface
{
    public function getAll(string $orderBy = 'id', string $direction = 'DESC'): array;

    public function getById(int $id): array|false;

    public function create(array $data): bool;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
