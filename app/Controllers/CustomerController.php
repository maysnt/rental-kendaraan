<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Helpers\Validator;
use App\Models\Customer;

class CustomerController extends Controller
{
    private Customer $customerModel;

    public function __construct()
    {
        Session::requireLogin();
        $this->customerModel = new Customer();
    }

    public function index(): void
    {
        $keyword   = trim((string) $this->input('q', ''));
        $sort      = (string) $this->input('sort', 'id');
        $direction = (string) $this->input('direction', 'DESC');
        $page      = max(1, (int) $this->input('page', 1));
        $limit     = 10;
        $offset    = ($page - 1) * $limit;

        $allowedSort = ['id', 'nama', 'no_hp', 'created_at'];

        if ($keyword !== '') {
            $data  = $this->customerModel->searchPaginated(['nama', 'no_hp', 'no_ktp', 'email'], $keyword, $limit, $offset);
            $total = $this->customerModel->countSearch(['nama', 'no_hp', 'no_ktp', 'email'], $keyword);
        } else {
            $data  = $this->customerModel->getPaginated($limit, $offset, $sort, $direction, $allowedSort);
            $total = $this->customerModel->countAll();
        }

        $this->view('customer/index', [
            'customerList' => $data,
            'keyword'      => $keyword,
            'sort'         => $sort,
            'direction'    => $direction,
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

        $this->view('customer/create');
    }

    public function edit(): void
    {
        $id       = (int) $this->input('id', 0);
        $customer = $this->customerModel->getById($id);

        if (!$customer) {
            $this->setFlash('error', 'Pelanggan tidak ditemukan.');
            $this->redirect('customer');
            return;
        }

        if ($this->isPost()) {
            $this->simpan($id);
            return;
        }

        $this->view('customer/edit', ['customer' => $customer]);
    }

    private function simpan(int $id = 0): void
    {
        $data = [
            'nama'    => trim((string) $this->input('nama', '')),
            'no_ktp'  => trim((string) $this->input('no_ktp', '')),
            'no_hp'   => trim((string) $this->input('no_hp', '')),
            'email'   => trim((string) $this->input('email', '')),
            'alamat'  => trim((string) $this->input('alamat', '')),
        ];

        $errors = Validator::make($data, [
            'nama'   => 'required|min:3',
            'no_ktp' => 'required|min:16|max:16',
            'no_hp'  => 'required|min:9',
            'email'  => 'email',
        ]);

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $this->setFlash('error', implode(' ', $errors));
            $this->redirect($id ? 'customer/edit' : 'customer/create', $id ? ['id' => $id] : []);
            return;
        }

        try {
            if ($id > 0) {
                $this->customerModel->update($id, $data);
                $this->setFlash('success', 'Data pelanggan berhasil diperbarui.');
            } else {
                $this->customerModel->create($data);
                $this->setFlash('success', 'Pelanggan baru berhasil ditambahkan.');
            }
            $this->redirect('customer');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal menyimpan data pelanggan (NIK mungkin sudah terdaftar): ' . $e->getMessage());
            $this->redirect($id ? 'customer/edit' : 'customer/create', $id ? ['id' => $id] : []);
        }
    }

    public function detail(): void
    {
        $id       = (int) $this->input('id', 0);
        $customer = $this->customerModel->getById($id);

        if (!$customer) {
            $this->setFlash('error', 'Pelanggan tidak ditemukan.');
            $this->redirect('customer');
            return;
        }

        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT t.*, k.merk, k.model, k.plat_nomor
             FROM transaksi_sewa t
             JOIN kendaraan k ON t.kendaraan_id = k.id
             WHERE t.customer_id = :id ORDER BY t.id DESC'
        );
        $stmt->execute(['id' => $id]);
        $riwayat = $stmt->fetchAll();

        $this->view('customer/detail', ['customer' => $customer, 'riwayat' => $riwayat]);
    }

    public function delete(): void
    {
        $id = (int) $this->input('id', 0);

        try {
            $this->customerModel->delete($id);
            $this->setFlash('success', 'Pelanggan berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Pelanggan tidak dapat dihapus, kemungkinan masih memiliki riwayat transaksi.');
        }

        $this->redirect('customer');
    }
}
