<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Helpers\Helper;
use App\Helpers\Validator;
use App\Models\KendaraanFactory;
use App\Models\Kategori;
use App\Models\Mobil;
use PDO;

class KendaraanController extends Controller
{
    /**
     * Catatan desain: Kendaraan adalah abstract class, sehingga untuk operasi
     * tabel generik (getAll/getById/create/update/delete yang diwarisi dari Model)
     * kita memakai instance konkret Mobil sebagai "gateway" tabel kendaraan.
     * Untuk representasi objek per baris yang benar (Mobil ATAU Motor),
     * selalu gunakan KendaraanFactory::buat()/buatBanyak() -- lihat di bawah.
     */
    private Mobil $kendaraanModel;
    private Kategori $kategoriModel;

    public function __construct()
    {
        Session::requireLogin();
        $this->kendaraanModel = new Mobil();
        $this->kategoriModel  = new Kategori();
    }

    public function index(): void
    {
        $keyword    = trim((string) $this->input('q', ''));
        $kategoriId = (int) $this->input('kategori_id', 0);
        $status     = trim((string) $this->input('status', ''));
        $sort       = (string) $this->input('sort', 'id');
        $direction  = (string) $this->input('direction', 'DESC');
        $page       = max(1, (int) $this->input('page', 1));
        $limit      = 9;
        $offset     = ($page - 1) * $limit;

        $allowedSort = ['id', 'merk', 'harga_sewa_harian', 'tahun'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'id';
        }
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        $where  = '1=1';
        $params = [];

        if ($kategoriId > 0) {
            $where               .= ' AND k.kategori_id = :kategori_id';
            $params[':kategori_id'] = $kategoriId;
        }
        if ($status !== '') {
            $where            .= ' AND k.status = :status';
            $params[':status'] = $status;
        }
        if ($keyword !== '') {
            $where        .= ' AND (k.merk LIKE :kw OR k.model LIKE :kw OR k.plat_nomor LIKE :kw)';
            $params[':kw'] = "%{$keyword}%";
        }

        $db = Database::getInstance()->getConnection();

        $sql  = "SELECT k.*, kt.nama_kategori FROM kendaraan k
                 LEFT JOIN kategori_kendaraan kt ON k.kategori_id = kt.id
                 WHERE {$where} ORDER BY k.{$sort} {$direction}
                 LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $stmtTotal = $db->prepare("SELECT COUNT(*) as total FROM kendaraan k WHERE {$where}");
        foreach ($params as $key => $value) {
            $stmtTotal->bindValue($key, $value);
        }
        $stmtTotal->execute();
        $total = (int) $stmtTotal->fetch()['total'];

        $kendaraanList = KendaraanFactory::buatBanyak($rows);
        $kategoriList  = $this->kategoriModel->getAll('nama_kategori', 'ASC');

        $this->view('kendaraan/index', [
            'kendaraanList' => $kendaraanList,
            'kategoriList'  => $kategoriList,
            'keyword'       => $keyword,
            'kategoriId'    => $kategoriId,
            'status'        => $status,
            'sort'          => $sort,
            'direction'     => $direction,
            'page'          => $page,
            'totalPage'     => max(1, (int) ceil($total / $limit)),
        ]);
    }

    public function create(): void
    {
        if ($this->isPost()) {
            $this->simpan();
            return;
        }

        $kategoriList = $this->kategoriModel->getAll('nama_kategori', 'ASC');
        $this->view('kendaraan/create', ['kategoriList' => $kategoriList]);
    }

    public function edit(): void
    {
        $id = (int) $this->input('id', 0);

        if ($this->isPost()) {
            $this->simpan($id);
            return;
        }

        $row = $this->kendaraanModel->getById($id);
        if (!$row) {
            $this->setFlash('error', 'Kendaraan tidak ditemukan.');
            $this->redirect('kendaraan');
            return;
        }

        $kendaraan    = KendaraanFactory::buat($row);
        $kategoriList = $this->kategoriModel->getAll('nama_kategori', 'ASC');

        $this->view('kendaraan/edit', [
            'kendaraan'    => $kendaraan,
            'row'          => $row,
            'kategoriList' => $kategoriList,
        ]);
    }

    private function simpan(int $id = 0): void
    {
        $data = [
            'kategori_id'         => (int) $this->input('kategori_id', 0) ?: null,
            'jenis'                => $this->input('jenis', 'mobil'),
            'kode_kendaraan'       => trim((string) $this->input('kode_kendaraan', '')),
            'merk'                 => trim((string) $this->input('merk', '')),
            'model'                => trim((string) $this->input('model', '')),
            'tahun'                => (int) $this->input('tahun', 0),
            'plat_nomor'           => strtoupper(trim((string) $this->input('plat_nomor', ''))),
            'harga_sewa_harian'    => (float) $this->input('harga_sewa_harian', 0),
            'status'               => $this->input('status', 'tersedia'),
            'kapasitas_penumpang'  => $this->input('kapasitas_penumpang') ?: null,
            'transmisi'            => $this->input('transmisi') ?: null,
            'kapasitas_cc'         => $this->input('kapasitas_cc') ?: null,
            'tipe_motor'           => $this->input('tipe_motor') ?: null,
            'deskripsi'            => trim((string) $this->input('deskripsi', '')),
        ];

        $errors = Validator::make($data, [
            'merk'              => 'required',
            'model'             => 'required',
            'plat_nomor'        => 'required',
            'harga_sewa_harian' => 'required|numeric',
        ]);

        if (!empty($errors)) {
            $_SESSION['old'] = $_POST;
            $this->setFlash('error', implode(' ', $errors));
            $this->redirect($id ? 'kendaraan/edit' : 'kendaraan/create', $id ? ['id' => $id] : []);
            return;
        }

        if (empty($data['kode_kendaraan'])) {
            $data['kode_kendaraan'] = Helper::generateKodeUnik('KND');
        }

        try {
            if (!empty($_FILES['foto']['name'])) {
                $namaFile = Helper::uploadFoto($_FILES['foto'], UPLOAD_PATH);
                if ($namaFile) {
                    $data['foto'] = $namaFile;
                }
            }

            if ($id > 0) {
                $this->kendaraanModel->update($id, $data);
                $this->setFlash('success', 'Data kendaraan berhasil diperbarui.');
            } else {
                $this->kendaraanModel->create($data);
                $this->setFlash('success', 'Data kendaraan berhasil ditambahkan.');
            }
            $this->redirect('kendaraan');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal menyimpan data kendaraan: ' . $e->getMessage());
            $this->redirect($id ? 'kendaraan/edit' : 'kendaraan/create', $id ? ['id' => $id] : []);
        }
    }

    public function detail(): void
    {
        $id  = (int) $this->input('id', 0);
        $row = $this->kendaraanModel->getById($id);

        if (!$row) {
            $this->setFlash('error', 'Kendaraan tidak ditemukan.');
            $this->redirect('kendaraan');
            return;
        }

        $kendaraan = KendaraanFactory::buat($row);
        $kategori  = $row['kategori_id'] ? $this->kategoriModel->getById((int) $row['kategori_id']) : false;

        $this->view('kendaraan/detail', [
            'kendaraan' => $kendaraan,
            'row'       => $row,
            'kategori'  => $kategori,
        ]);
    }

    public function delete(): void
    {
        $id  = (int) $this->input('id', 0);
        $row = $this->kendaraanModel->getById($id);

        try {
            $this->kendaraanModel->delete($id);

            if ($row && !empty($row['foto']) && file_exists(UPLOAD_PATH . $row['foto'])) {
                unlink(UPLOAD_PATH . $row['foto']);
            }

            $this->setFlash('success', 'Kendaraan berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Kendaraan tidak dapat dihapus, kemungkinan masih memiliki riwayat transaksi sewa.');
        }

        $this->redirect('kendaraan');
    }
}
