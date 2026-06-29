<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Helpers\Helper;
use App\Helpers\Validator;
use App\Models\Customer;
use App\Models\KendaraanFactory;
use App\Models\Mobil;
use App\Models\TransaksiSewa;

class TransaksiController extends Controller
{
    private TransaksiSewa $transaksiModel;
    private Customer $customerModel;
    private Mobil $kendaraanModel; // gateway tabel kendaraan, lihat catatan di KendaraanController

    public function __construct()
    {
        Session::requireLogin();
        $this->transaksiModel = new TransaksiSewa();
        $this->customerModel  = new Customer();
        $this->kendaraanModel = new Mobil();
    }

    public function index(): void
    {
        $keyword = trim((string) $this->input('q', ''));
        $status  = trim((string) $this->input('status', ''));
        $page    = max(1, (int) $this->input('page', 1));
        $limit   = 10;
        $offset  = ($page - 1) * $limit;

        $data  = $this->transaksiModel->getPaginatedWithRelasi($limit, $offset, $status, $keyword);
        $total = $this->transaksiModel->countFiltered($status, $keyword);

        $this->view('transaksi/index', [
            'transaksiList' => $data,
            'keyword'       => $keyword,
            'status'        => $status,
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

        $db           = Database::getInstance()->getConnection();
        $customerList = $this->customerModel->getAll('nama', 'ASC');
        $rows         = $db->query("SELECT * FROM kendaraan WHERE status = 'tersedia' ORDER BY merk ASC")->fetchAll();
        $kendaraanList = KendaraanFactory::buatBanyak($rows);

        $this->view('transaksi/create', [
            'customerList'  => $customerList,
            'kendaraanList' => $kendaraanList,
        ]);
    }

    private function simpan(): void
    {
        $customerId      = (int) $this->input('customer_id', 0);
        $kendaraanId     = (int) $this->input('kendaraan_id', 0);
        $tanggalSewa     = (string) $this->input('tanggal_sewa', '');
        $tanggalRencana  = (string) $this->input('tanggal_kembali_rencana', '');
        $catatan         = trim((string) $this->input('catatan', ''));

        $errors = Validator::make(
            [
                'customer_id'              => $customerId,
                'kendaraan_id'             => $kendaraanId,
                'tanggal_sewa'             => $tanggalSewa,
                'tanggal_kembali_rencana' => $tanggalRencana,
            ],
            [
                'customer_id'              => 'required',
                'kendaraan_id'             => 'required',
                'tanggal_sewa'             => 'required',
                'tanggal_kembali_rencana' => 'required',
            ]
        );

        if (!empty($errors)) {
            $this->setFlash('error', implode(' ', $errors));
            $this->redirect('transaksi/create');
            return;
        }

        if (strtotime($tanggalRencana) <= strtotime($tanggalSewa)) {
            $this->setFlash('error', 'Tanggal rencana kembali harus setelah tanggal sewa.');
            $this->redirect('transaksi/create');
            return;
        }

        try {
            $kendaraanRow = $this->kendaraanModel->getById($kendaraanId);

            if (!$kendaraanRow || $kendaraanRow['status'] !== 'tersedia') {
                $this->setFlash('error', 'Kendaraan yang dipilih sudah tidak tersedia.');
                $this->redirect('transaksi/create');
                return;
            }

            // POLYMORPHISM: $kendaraan bisa berupa Mobil atau Motor,
            // hitungBiayaSewa() akan otomatis memanggil implementasi yang sesuai.
            $kendaraan   = KendaraanFactory::buat($kendaraanRow);
            $lamaHari    = $this->transaksiModel->hitungLamaHari($tanggalSewa, $tanggalRencana);
            $totalBiaya  = $kendaraan->hitungBiayaSewa($lamaHari);

            $this->transaksiModel->create([
                'kode_transaksi'           => TransaksiSewa::generateKodeTransaksi(),
                'customer_id'              => $customerId,
                'kendaraan_id'             => $kendaraanId,
                'user_id'                  => Session::get('user_id'),
                'tanggal_sewa'             => $tanggalSewa,
                'tanggal_kembali_rencana' => $tanggalRencana,
                'lama_sewa'                => $lamaHari,
                'total_biaya'              => $totalBiaya,
                'status'                   => 'berjalan',
                'catatan'                  => $catatan,
            ]);

            $this->kendaraanModel->update($kendaraanId, ['status' => 'disewa']);

            $this->setFlash('success', 'Transaksi sewa berhasil dibuat. Total biaya: ' . Helper::formatRupiah($totalBiaya));
            $this->redirect('transaksi');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal membuat transaksi: ' . $e->getMessage());
            $this->redirect('transaksi/create');
        }
    }

    public function edit(): void
    {
        $id        = (int) $this->input('id', 0);
        $transaksi = $this->transaksiModel->getById($id);

        if (!$transaksi) {
            $this->setFlash('error', 'Transaksi tidak ditemukan.');
            $this->redirect('transaksi');
            return;
        }

        if ($this->isPost()) {
            $tanggalRencana = (string) $this->input('tanggal_kembali_rencana', '');
            $catatan        = trim((string) $this->input('catatan', ''));

            try {
                $kendaraanRow = $this->kendaraanModel->getById((int) $transaksi['kendaraan_id']);
                $kendaraan    = KendaraanFactory::buat($kendaraanRow);
                $lamaHari     = $this->transaksiModel->hitungLamaHari($transaksi['tanggal_sewa'], $tanggalRencana);
                $totalBiaya   = $kendaraan->hitungBiayaSewa($lamaHari);

                $this->transaksiModel->update($id, [
                    'tanggal_kembali_rencana' => $tanggalRencana,
                    'lama_sewa'                => $lamaHari,
                    'total_biaya'              => $totalBiaya,
                    'catatan'                  => $catatan,
                ]);

                $this->setFlash('success', 'Transaksi berhasil diperbarui.');
                $this->redirect('transaksi');
            } catch (\Throwable $e) {
                $this->setFlash('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
                $this->redirect('transaksi/edit', ['id' => $id]);
            }
            return;
        }

        $this->view('transaksi/edit', ['transaksi' => $transaksi]);
    }

    public function detail(): void
    {
        $id        = (int) $this->input('id', 0);
        $transaksi = $this->transaksiModel->getByIdWithRelasi($id);

        if (!$transaksi) {
            $this->setFlash('error', 'Transaksi tidak ditemukan.');
            $this->redirect('transaksi');
            return;
        }

        $this->view('transaksi/detail', ['transaksi' => $transaksi]);
    }

    /**
     * Export PDF sederhana: tampilkan halaman cetak/struk yang ramah print,
     * pengguna tinggal Ctrl+P -> Save as PDF dari browser.
     */
    public function cetak(): void
    {
        $id        = (int) $this->input('id', 0);
        $transaksi = $this->transaksiModel->getByIdWithRelasi($id);

        if (!$transaksi) {
            $this->setFlash('error', 'Transaksi tidak ditemukan.');
            $this->redirect('transaksi');
            return;
        }

        $this->view('transaksi/cetak', ['transaksi' => $transaksi]);
    }

    public function selesai(): void
    {
        $id        = (int) $this->input('id', 0);
        $transaksi = $this->transaksiModel->getById($id);

        if (!$transaksi) {
            $this->setFlash('error', 'Transaksi tidak ditemukan.');
            $this->redirect('transaksi');
            return;
        }

        try {
            $tanggalAktual = date('Y-m-d');
            $kendaraanRow  = $this->kendaraanModel->getById((int) $transaksi['kendaraan_id']);
            $denda         = $this->transaksiModel->hitungDenda(
                $transaksi['tanggal_kembali_rencana'],
                $tanggalAktual,
                (float) ($kendaraanRow['harga_sewa_harian'] ?? 0)
            );

            $this->transaksiModel->update($id, [
                'tanggal_kembali_aktual' => $tanggalAktual,
                'denda'                   => $denda,
                'status'                  => 'selesai',
            ]);

            $this->kendaraanModel->update((int) $transaksi['kendaraan_id'], ['status' => 'tersedia']);

            $pesan = $denda > 0
                ? 'Transaksi diselesaikan dengan denda keterlambatan ' . Helper::formatRupiah($denda) . '.'
                : 'Transaksi berhasil diselesaikan tanpa denda.';
            $this->setFlash('success', $pesan);
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal menyelesaikan transaksi: ' . $e->getMessage());
        }

        $this->redirect('transaksi');
    }

    public function batal(): void
    {
        $id        = (int) $this->input('id', 0);
        $transaksi = $this->transaksiModel->getById($id);

        if ($transaksi) {
            try {
                $this->transaksiModel->update($id, ['status' => 'batal']);
                $this->kendaraanModel->update((int) $transaksi['kendaraan_id'], ['status' => 'tersedia']);
                $this->setFlash('success', 'Transaksi berhasil dibatalkan.');
            } catch (\Throwable $e) {
                $this->setFlash('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
            }
        }

        $this->redirect('transaksi');
    }

    public function delete(): void
    {
        $id = (int) $this->input('id', 0);

        try {
            $this->transaksiModel->delete($id);
            $this->setFlash('success', 'Riwayat transaksi berhasil dihapus.');
        } catch (\Throwable $e) {
            $this->setFlash('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }

        $this->redirect('transaksi');
    }
}
