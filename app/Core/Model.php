<?php

namespace App\Core;

use App\Interfaces\CRUDInterface;
use PDO;
use PDOException;

/**
 * Model (abstract)
 *
 * Base class untuk seluruh Model data (Kendaraan, Customer, TransaksiSewa, dst).
 *
 * - ABSTRACTION: class ini abstract dan mengimplementasikan interface CRUDInterface.
 *   Anak class WAJIB mengisi getTableName(), sisanya sudah disediakan (reusable component).
 * - ENCAPSULATION: properti $db dan $table protected, hanya bisa diakses turunannya.
 * - EXCEPTION HANDLING: create/update/delete dibungkus try-catch terhadap PDOException
 *   (misalnya melanggar foreign key constraint saat menghapus data yang masih dipakai).
 */
abstract class Model implements CRUDInterface
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db    = Database::getInstance()->getConnection();
        $this->table = $this->getTableName();
    }

    /**
     * Setiap Model anak wajib menentukan nama tabelnya sendiri.
     */
    abstract protected function getTableName(): string;

    public function getAll(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';
        $orderBy   = preg_replace('/[^a-zA-Z0-9_]/', '', $orderBy);
        $sql       = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}";

        return $this->db->query($sql)->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: false;
    }

    public function create(array $data): bool
    {
        $columns      = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql          = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log('[Model::create] ' . $e->getMessage());
            throw new \RuntimeException('Gagal menyimpan data: data mungkin duplikat atau tidak valid.');
        }
    }

    public function update(int $id, array $data): bool
    {
        $set = implode(', ', array_map(static fn ($col) => "{$col} = :{$col}", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE id = :id";
        $data['id'] = $id;

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log('[Model::update] ' . $e->getMessage());
            throw new \RuntimeException('Gagal memperbarui data: data mungkin duplikat atau tidak valid.');
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log('[Model::delete] ' . $e->getMessage());
            throw new \RuntimeException('Data tidak dapat dihapus karena masih digunakan/direlasikan oleh data lain.');
        }
    }

    public function countAll(): int
    {
        $row = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}")->fetch();
        return (int) $row['total'];
    }

    public function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }

    /**
     * Helper generik untuk PAGINATION + SORTING (fitur tambahan wajib).
     * Reusable: dipakai oleh seluruh Model anak tanpa menulis ulang query.
     */
    public function getPaginated(int $limit, int $offset, string $orderBy, string $direction, array $allowedOrderColumns): array
    {
        if (!in_array($orderBy, $allowedOrderColumns, true)) {
            $orderBy = $allowedOrderColumns[0];
        }
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        $sql  = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Helper generik untuk SEARCH DATA (fitur tambahan wajib) dengan pagination.
     */
    public function searchPaginated(array $searchColumns, string $keyword, int $limit, int $offset): array
    {
        $likeClauses = implode(' OR ', array_map(static fn ($col) => "{$col} LIKE :kw", $searchColumns));
        $sql = "SELECT * FROM {$this->table} WHERE ({$likeClauses}) ORDER BY id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':kw', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countSearch(array $searchColumns, string $keyword): int
    {
        $likeClauses = implode(' OR ', array_map(static fn ($col) => "{$col} LIKE :kw", $searchColumns));
        $sql  = "SELECT COUNT(*) as total FROM {$this->table} WHERE ({$likeClauses})";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':kw', "%{$keyword}%", PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetch()['total'];
    }
}
