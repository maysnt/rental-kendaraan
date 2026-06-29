<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\TransaksiSewa;

class DashboardController extends Controller
{
    public function __construct()
    {
        Session::requireLogin();
    }

    public function index(): void
    {
        $db = Database::getInstance()->getConnection();

        $totalKendaraan       = (int) $db->query("SELECT COUNT(*) as total FROM kendaraan")->fetch()['total'];
        $kendaraanTersedia    = (int) $db->query("SELECT COUNT(*) as total FROM kendaraan WHERE status = 'tersedia'")->fetch()['total'];
        $kendaraanDisewa      = (int) $db->query("SELECT COUNT(*) as total FROM kendaraan WHERE status = 'disewa'")->fetch()['total'];
        $kendaraanMaintenance = (int) $db->query("SELECT COUNT(*) as total FROM kendaraan WHERE status = 'maintenance'")->fetch()['total'];
        $totalCustomer        = (int) $db->query("SELECT COUNT(*) as total FROM customers")->fetch()['total'];
        $transaksiAktif       = (int) $db->query("SELECT COUNT(*) as total FROM transaksi_sewa WHERE status = 'berjalan'")->fetch()['total'];

        $pendapatanBulanIni = (float) $db->query(
            "SELECT COALESCE(SUM(total_biaya + denda), 0) as total FROM transaksi_sewa
             WHERE status = 'selesai' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"
        )->fetch()['total'];

        $transaksiTerbaru = $db->query(
            "SELECT t.*, c.nama AS nama_customer, k.merk, k.model
             FROM transaksi_sewa t
             JOIN customers c ON t.customer_id = c.id
             JOIN kendaraan k ON t.kendaraan_id = k.id
             ORDER BY t.id DESC LIMIT 5"
        )->fetchAll();

        $transaksiModel  = new TransaksiSewa();
        $statistikBulanan = $transaksiModel->getStatistikBulanan(6);

        $this->view('dashboard/index', [
            'totalKendaraan'       => $totalKendaraan,
            'kendaraanTersedia'    => $kendaraanTersedia,
            'kendaraanDisewa'      => $kendaraanDisewa,
            'kendaraanMaintenance' => $kendaraanMaintenance,
            'totalCustomer'        => $totalCustomer,
            'transaksiAktif'       => $transaksiAktif,
            'pendapatanBulanIni'   => $pendapatanBulanIni,
            'transaksiTerbaru'     => $transaksiTerbaru,
            'statistikBulanan'     => $statistikBulanan,
        ]);
    }
}
