<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Helpers\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
            return;
        }

        if ($this->isPost()) {
            $this->proses();
            return;
        }

        $this->view('auth/login');
    }

    private function proses(): void
    {
        $username = trim((string) $this->input('username', ''));
        $password = (string) $this->input('password', '');

        $errors = Validator::make(
            ['username' => $username, 'password' => $password],
            ['username' => 'required', 'password' => 'required']
        );

        if (!empty($errors)) {
            $this->setFlash('error', 'Username dan password wajib diisi.');
            $this->redirect('login');
            return;
        }

        try {
            $user = User::buatDariUsername($username);

            if (!$user || !User::verifikasiPassword($password, $user->getPassword())) {
                $this->setFlash('error', 'Username atau password salah.');
                $this->redirect('login');
                return;
            }

            Session::login($user);
            $this->setFlash('success', 'Selamat datang, ' . $user->getNama() . '!');
            $this->redirect('dashboard');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Terjadi kesalahan saat proses login: ' . $e->getMessage());
            $this->redirect('login');
        }
    }

    public function logout(): void
    {
        Session::logout();
        $this->redirect('login');
    }
}
