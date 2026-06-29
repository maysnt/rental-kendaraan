<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * TransaksiSewa
 *
 * Representasi tabel transaksi_sewa, sekaligus berisi business logic inti
 * dari aplikasi rental: menghitung lama sewa & menghitung denda keterlambatan.
 */
class TransaksiSewa extends Model
{
    protected function getTableName(): string
    {
        return 'transaksi_sewa';
    }

    /**
     * STATIC METHOD: menghasilkan kode transaksi unik, mis. TRX2026062612345
     */
    public static function generateKodeTransaksi(): string
    {
        return 'TRX' . date('Ymd') . strtoupper(substr(uniqid(), -5));
    }

    public function hitungLamaHari(string $tanggalSewa, string $tanggalKembali): int
    {
        $mulai   = new \DateTime($tanggalSewa);
        $selesai = new \DateTime($tanggalKembali);
        $selisih = (int) $mulai->diff($selesai)->days;

        return max($selisih, 1);
    }

    /**
     * Denda dihitung 50% dari harga sewa harian, dikali jumlah hari keterlambatan.
     */
    public function hitungDenda(string $tanggalRencana, string $tanggalAktual, float $hargaHarian): float
    {
        $rencana = new \DateTime($tanggalRencana);
        $aktual  = new \DateTime($tanggalAktual);

        if ($aktual <= $rencana) {
            return 0.0;
        }

        $terlambat = (int) $rencana->diff($aktual)->days;

        return $terlambat * $hargaHarian * 0.5;
    }

    public function getByIdWithRelasi(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, c.nama AS nama_customer, c.no_hp, c.no_ktp, c.alamat,
                    k.merk, k.model, k.plat_nomor, k.jenis, k.harga_sewa_harian, k.foto,
                    u.nama AS nama_petugas
             FROM transaksi_sewa t
             JOIN customers c ON t.customer_id = c.id
             JOIN kendaraan k ON t.kendaraan_id = k.id
             LEFT JOIN users u ON t.user_id = u.id
             WHERE t.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: false;
    }

    /**
     * List transaksi dengan join + filter status + search + pagination sekaligus
     * (memenuhi fitur tambahan: filter, search, pagination, sorting berdasarkan id terbaru).
     */
    public function getPaginatedWithRelasi(int $limit, int $offset, string $status = '', string $keyword = ''): array
    {
        [$where, $params] = $this->bangunFilter($status, $keyword);

        $sql = "SELECT t.*, c.nama AS nama_customer, k.merk, k.model, k.plat_nomor, k.jenis
                FROM transaksi_sewa t
                JOIN customers c ON t.customer_id = c.id
                JOIN kendaraan k ON t.kendaraan_id = k.id
                WHERE {$where}
                ORDER BY t.id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countFiltered(string $status = '', string $keyword = ''): int
    {
        [$where, $params] = $this->bangunFilter($status, $keyword);

        $sql = "SELECT COUNT(*) as total
                FROM transaksi_sewa t
                JOIN customers c ON t.customer_id = c.id
                JOIN kendaraan k ON t.kendaraan_id = k.id
                WHERE {$where}";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return (int) $stmt->fetch()['total'];
    }

    /**
     * Statistik pendapatan bulanan untuk dashboard chart sederhana.
     */
    public function getStatistikBulanan(int $bulanTerakhir = 6): array
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan, SUM(total_biaya + denda) as total
                FROM transaksi_sewa
                WHERE status = 'selesai'
                GROUP BY bulan
                ORDER BY bulan DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $bulanTerakhir, PDO::PARAM_INT);
        $stmt->execute();

        return array_reverse($stmt->fetchAll());
    }

    /**
     * @return array{0: string, 1: array<string, mixed>}
     */
    private function bangunFilter(string $status, string $keyword): array
    {
        $where  = '1=1';
        $params = [];

        if ($status !== '') {
            $where           .= ' AND t.status = :status';
            $params[':status'] = $status;
        }

        if ($keyword !== '') {
            $where        .= ' AND (t.kode_transaksi LIKE :kw OR c.nama LIKE :kw OR k.merk LIKE :kw OR k.plat_nomor LIKE :kw)';
            $params[':kw'] = "%{$keyword}%";
        }

        return [$where, $params];
    }
}
