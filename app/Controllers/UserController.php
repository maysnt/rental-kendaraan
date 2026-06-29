<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Helpers\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        Session::requireRole(['admin']);
    }

    public function index(): void
    {
        $db   = Database::getInstance()->getConnection();
        $rows = $db->query('SELECT * FROM users ORDER BY id DESC')->fetchAll();

        $this->view('user/index', ['userList' => $rows]);
    }

    public function create(): void
    {
        if ($this->isPost()) {
            $this->simpan();
            return;
        }

        $this->view('user/create');
    }

    public function edit(): void
    {
        $id   = (int) $this->input('id', 0);
        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if (!$user) {
            $this->setFlash('error', 'Akun tidak ditemukan.');
            $this->redirect('user');
            return;
        }

        if ($this->isPost()) {
            $this->simpan($id);
            return;
        }

        $this->view('user/edit', ['user' => $user]);
    }

    private function simpan(int $id = 0): void
    {
        $nama     = trim((string) $this->input('nama', ''));
        $username = trim((string) $this->input('username', ''));
        $password = (string) $this->input('password', '');
        $role     = (string) $this->input('role', 'petugas');

        $rules = ['nama' => 'required', 'username' => 'required|min:4'];
        if ($id === 0) {
            $rules['password'] = 'required|min:6';
        }

        $errors = Validator::make(
            ['nama' => $nama, 'username' => $username, 'password' => $password],
            $rules
        );

        if (!empty($errors)) {
            $this->setFlash('error', implode(' ', $errors));
            $this->redirect($id ? 'user/edit' : 'user/create', $id ? ['id' => $id] : []);
            return;
        }

        $db = Database::getInstance()->getConnection();

        try {
            if ($id > 0) {
                $sql    = 'UPDATE users SET nama = :nama, username = :username, role = :role';
                $params = ['nama' => $nama, 'username' => $username, 'role' => $role, 'id' => $id];

                if ($password !== '') {
                    $sql              .= ', password = :password';
                    $params['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $sql .= ' WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->execute($params);

                $this->setFlash('success', 'Akun berhasil diperbarui.');
            } else {
                $stmt = $db->prepare(
                    'INSERT INTO users (nama, username, password, role) VALUES (:nama, :username, :password, :role)'
                );
                $stmt->execute([
                    'nama'     => $nama,
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role'     => $role,
                ]);

                $this->setFlash('success', 'Akun baru berhasil ditambahkan.');
            }
            $this->redirect('user');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal menyimpan akun, username mungkin sudah digunakan.');
            $this->redirect($id ? 'user/edit' : 'user/create', $id ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        $id = (int) $this->input('id', 0);

        if ($id === (int) Session::get('user_id')) {
            $this->setFlash('error', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.');
            $this->redirect('user');
            return;
        }

        try {
            $db   = Database::getInstance()->getConnection();
            $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $this->setFlash('success', 'Akun berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }

        $this->redirect('user');
    }
}
