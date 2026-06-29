<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

/**
 * User (abstract)
 *
 * INHERITANCE: induk dari Admin dan Petugas.
 * ABSTRACTION: method getMenuAkses() abstract, wajib diisi beda-beda oleh setiap turunan.
 * ENCAPSULATION: seluruh properti private/protected, hanya bisa diakses lewat getter.
 */
abstract class User extends Model
{
    protected int $id = 0;
    protected string $nama = '';
    protected string $username = '';
    private string $password = '';
    protected string $role = '';

    protected function getTableName(): string
    {
        return 'users';
    }

    public function setDataFromArray(array $row): static
    {
        $this->id       = (int) ($row['id'] ?? 0);
        $this->nama     = $row['nama'] ?? '';
        $this->username = $row['username'] ?? '';
        $this->password = $row['password'] ?? '';
        $this->role     = $row['role'] ?? '';

        return $this;
    }

    /**
     * POLYMORPHISM: setiap role punya daftar menu yang berbeda.
     * Diimplementasikan berbeda di Admin::getMenuAkses() dan Petugas::getMenuAkses().
     *
     * @return string[]
     */
    abstract public function getMenuAkses(): array;

    public function getId(): int
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * STATIC METHOD: factory untuk membuat objek Admin/Petugas yang tepat
     * berdasarkan kolom "role" pada tabel users, lalu mengembalikannya
     * sebagai objek User (polymorphism) atau null bila username tidak ditemukan.
     */
    public static function buatDariUsername(string $username): ?User
    {
        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $user = $row['role'] === 'admin' ? new Admin() : new Petugas();

        return $user->setDataFromArray($row);
    }

    public static function verifikasiPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }
}
