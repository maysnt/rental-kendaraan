<?php

use App\Helpers\Helper;

$pageTitle = 'Detail Pelanggan';
require __DIR__ . '/../layouts/header.php';
?>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="rk-card p-4">
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($customer['nama']) ?></h5>
            <div class="text-muted small mb-3">Terdaftar sejak <?= Helper::formatTanggalIndo($customer['created_at']) ?></div>
            <div class="mb-2"><span class="small text-muted d-block">No. KTP</span><?= htmlspecialchars($customer['no_ktp']) ?></div>
            <div class="mb-2"><span class="small text-muted d-block">No. HP</span><?= htmlspecialchars($customer['no_hp']) ?></div>
            <div class="mb-2"><span class="small text-muted d-block">Email</span><?= htmlspecialchars($customer['email'] ?? '-') ?></div>
            <div class="mb-2"><span class="small text-muted d-block">Alamat</span><?= nl2br(htmlspecialchars($customer['alamat'] ?? '-')) ?></div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="rk-card p-0">
            <div class="p-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0">Riwayat Transaksi Sewa</h6>
            </div>
            <div class="table-responsive">
                <table class="table rk-table mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kendaraan</th>
                            <th>Tanggal Sewa</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($riwayat)): ?>
                            <tr><td colspan="5" class="rk-empty">Belum ada riwayat transaksi.</td></tr>
                        <?php else: ?>
                            <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($r['kode_transaksi']) ?></td>
                                    <td><?= htmlspecialchars($r['merk'] . ' ' . $r['model']) ?></td>
                                    <td><?= Helper::formatTanggalIndo($r['tanggal_sewa']) ?></td>
                                    <td><?= Helper::statusBadge($r['status']) ?></td>
                                    <td><?= Helper::formatRupiah((float) $r['total_biaya'] + (float) $r['denda']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="<?= Helper::url('customer') ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
