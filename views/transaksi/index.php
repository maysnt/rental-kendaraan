<?php

use App\Helpers\Helper;

$pageTitle = 'Transaksi Sewa';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form class="d-flex gap-2" method="GET" action="">
        <input type="hidden" name="route" value="transaksi">
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="Cari kode, pelanggan, kendaraan..." style="width:260px;">
        <select name="status" class="form-select" style="width:160px;">
            <option value="">Semua Status</option>
            <option value="berjalan" <?= $status === 'berjalan' ? 'selected' : '' ?>>Berjalan</option>
            <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="batal" <?= $status === 'batal' ? 'selected' : '' ?>>Batal</option>
        </select>
        <button class="btn btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <a href="<?= Helper::url('transaksi/create') ?>" class="btn btn-rk-amber">
        <i class="fa-solid fa-plus me-1"></i> Buat Transaksi
    </a>
</div>

<div class="rk-card p-0">
    <div class="table-responsive">
        <table class="table rk-table mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Tgl Sewa</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th style="width:220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transaksiList)): ?>
                    <tr><td colspan="8" class="rk-empty">Belum ada transaksi.</td></tr>
                <?php else: ?>
                    <?php foreach ($transaksiList as $t): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($t['kode_transaksi']) ?></td>
                            <td><?= htmlspecialchars($t['nama_customer']) ?></td>
                            <td><?= htmlspecialchars($t['merk'] . ' ' . $t['model']) ?></td>
                            <td><?= Helper::formatTanggalIndo($t['tanggal_sewa']) ?></td>
                            <td><?= Helper::formatTanggalIndo($t['tanggal_kembali_rencana']) ?></td>
                            <td><?= Helper::statusBadge($t['status']) ?></td>
                            <td><?= Helper::formatRupiah((float) $t['total_biaya'] + (float) $t['denda']) ?></td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="<?= Helper::url('transaksi/detail', ['id' => $t['id']]) ?>" class="btn btn-sm btn-outline-secondary" title="Detail"><i class="fa-solid fa-eye"></i></a>
                                    <?php if ($t['status'] === 'berjalan'): ?>
                                        <a href="<?= Helper::url('transaksi/edit', ['id' => $t['id']]) ?>" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                        <a href="<?= Helper::url('transaksi/selesai', ['id' => $t['id']]) ?>" class="btn btn-sm btn-outline-success rk-confirm-delete" data-confirm="Tandai transaksi ini sebagai selesai (kendaraan dikembalikan)?" title="Selesai"><i class="fa-solid fa-check"></i></a>
                                        <a href="<?= Helper::url('transaksi/batal', ['id' => $t['id']]) ?>" class="btn btn-sm btn-outline-warning rk-confirm-delete" data-confirm="Batalkan transaksi ini?" title="Batal"><i class="fa-solid fa-ban"></i></a>
                                    <?php endif; ?>
                                    <a href="<?= Helper::url('transaksi/cetak', ['id' => $t['id']]) ?>" class="btn btn-sm btn-outline-secondary" title="Cetak" target="_blank"><i class="fa-solid fa-print"></i></a>
                                    <a href="<?= Helper::url('transaksi/delete', ['id' => $t['id']]) ?>" class="btn btn-sm btn-outline-danger rk-confirm-delete" data-confirm="Hapus riwayat transaksi ini?" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPage > 1): ?>
    <nav class="mt-3">
        <ul class="pagination rk-pagination">
            <?php for ($p = 1; $p <= $totalPage; $p++): ?>
                <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= Helper::url('transaksi', ['page' => $p, 'q' => $keyword, 'status' => $status]) ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
