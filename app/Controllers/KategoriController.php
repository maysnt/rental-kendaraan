<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Helpers\Validator;
use App\Models\Kategori;

class KategoriController extends Controller
{
    private Kategori $kategoriModel;

    public function __construct()
    {
        Session::requireLogin();
        $this->kategoriModel = new Kategori();
    }

    public function index(): void
    {
        $keyword = trim((string) $this->input('q', ''));
        $page    = max(1, (int) $this->input('page', 1));
        $limit   = 10;
        $offset  = ($page - 1) * $limit;

        if ($keyword !== '') {
            $data  = $this->kategoriModel->searchPaginated(['nama_kategori', 'deskripsi'], $keyword, $limit, $offset);
            $total = $this->kategoriModel->countSearch(['nama_kategori', 'deskripsi'], $keyword);
        } else {
            $data  = $this->kategoriModel->getPaginated($limit, $offset, 'id', 'DESC', ['id', 'nama_kategori']);
            $total = $this->kategoriModel->countAll();
        }

        $this->view('kategori/index', [
            'kategoriList' => $data,
            'keyword'      => $keyword,
            'page'         => $page,
            'totalPage'    => max(1, (int) ceil($total / $limit)),
        ]);
    }

    public function create(): void
    {
        if ($this->isPost()) {
            $this->simpan();
            return;
        }

        $this->view('kategori/create');
    }

    public function edit(): void
    {
        $id       = (int) $this->input('id', 0);
        $kategori = $this->kategoriModel->getById($id);

        if (!$kategori) {
            $this->setFlash('error', 'Kategori tidak ditemukan.');
            $this->redirect('kategori');
            return;
        }

        if ($this->isPost()) {
            $this->simpan($id);
            return;
        }

        $this->view('kategori/edit', ['kategori' => $kategori]);
    }

    private function simpan(int $id = 0): void
    {
        $namaKategori = trim((string) $this->input('nama_kategori', ''));
        $deskripsi    = trim((string) $this->input('deskripsi', ''));

        $errors = Validator::make(
            ['nama_kategori' => $namaKategori],
            ['nama_kategori' => 'required|min:3']
        );

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $this->setFlash('error', implode(' ', $errors));
            $this->redirect($id ? 'kategori/edit' : 'kategori/create', $id ? ['id' => $id] : []);
            return;
        }

        $data = ['nama_kategori' => $namaKategori, 'deskripsi' => $deskripsi];

        try {
            if ($id > 0) {
                $this->kategoriModel->update($id, $data);
                $this->setFlash('success', 'Kategori berhasil diperbarui.');
            } else {
                $this->kategoriModel->create($data);
                $this->setFlash('success', 'Kategori baru berhasil ditambahkan.');
            }
            $this->redirect('kategori');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal menyimpan kategori: ' . $e->getMessage());
            $this->redirect($id ? 'kategori/edit' : 'kategori/create', $id ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        $id = (int) $this->input('id', 0);

        try {
            $this->kategoriModel->delete($id);
            $this->setFlash('success', 'Kategori berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->setFlash('error', $e->getMessage());
        }

        $this->redirect('kategori');
    }
}
