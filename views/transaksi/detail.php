<?php

use App\Helpers\Helper;

$pageTitle = 'Detail Transaksi';
require __DIR__ . '/../layouts/header.php';
?>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="rk-card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($transaksi['kode_transaksi']) ?></h5>
                    <div class="text-muted small">Dibuat <?= Helper::formatTanggalIndo($transaksi['created_at']) ?> oleh <?= htmlspecialchars($transaksi['nama_petugas'] ?? '-') ?></div>
                </div>
                <?= Helper::statusBadge($transaksi['status']) ?>
            </div>

            <h6 class="fw-bold small text-uppercase text-muted mb-2">Pelanggan</h6>
            <div class="mb-3">
                <div class="fw-semibold"><?= htmlspecialchars($transaksi['nama_customer']) ?></div>
                <div class="small text-muted"><?= htmlspecialchars($transaksi['no_hp']) ?> &middot; KTP <?= htmlspecialchars($transaksi['no_ktp']) ?></div>
                <div class="small text-muted"><?= htmlspecialchars($transaksi['alamat'] ?? '-') ?></div>
            </div>

            <h6 class="fw-bold small text-uppercase text-muted mb-2">Kendaraan</h6>
            <div class="mb-3">
                <div class="fw-semibold"><?= htmlspecialchars($transaksi['merk'] . ' ' . $transaksi['model']) ?> (<?= ucfirst($transaksi['jenis']) ?>)</div>
                <div class="small text-muted">Plat <?= htmlspecialchars($transaksi['plat_nomor']) ?> &middot; <?= Helper::formatRupiah($transaksi['harga_sewa_harian']) ?>/hari</div>
            </div>

            <?php if (!empty($transaksi['catatan'])): ?>
                <h6 class="fw-bold small text-uppercase text-muted mb-2">Catatan</h6>
                <p class="small"><?= nl2br(htmlspecialchars($transaksi['catatan'])) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="rk-card p-4 mb-3">
            <h6 class="fw-bold mb-3">Rincian Sewa</h6>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Tanggal Sewa</span>
                <span><?= Helper::formatTanggalIndo($transaksi['tanggal_sewa']) ?></span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Rencana Kembali</span>
                <span><?= Helper::formatTanggalIndo($transaksi['tanggal_kembali_rencana']) ?></span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Tanggal Kembali Aktual</span>
                <span><?= Helper::formatTanggalIndo($transaksi['tanggal_kembali_aktual'] ?? null) ?></span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Lama Sewa</span>
                <span><?= (int) $transaksi['lama_sewa'] ?> hari</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Biaya Sewa</span>
                <span><?= Helper::formatRupiah($transaksi['total_biaya']) ?></span>
            </div>
            <div class="d-flex justify-content-between small mb-2">
                <span class="text-muted">Denda Keterlambatan</span>
                <span class="<?= $transaksi['denda'] > 0 ? 'text-danger' : '' ?>"><?= Helper::formatRupiah($transaksi['denda']) ?></span>
            </div>
            <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                <span>Total</span>
                <span><?= Helper::formatRupiah((float) $transaksi['total_biaya'] + (float) $transaksi['denda']) ?></span>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <?php if ($transaksi['status'] === 'berjalan'): ?>
                <a href="<?= Helper::url('transaksi/edit', ['id' => $transaksi['id']]) ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-pen me-1"></i>Edit</a>
                <a href="<?= Helper::url('transaksi/selesai', ['id' => $transaksi['id']]) ?>" class="btn btn-success rk-confirm-delete" data-confirm="Tandai sebagai selesai?"><i class="fa-solid fa-check me-1"></i>Selesaikan</a>
                <a href="<?= Helper::url('transaksi/batal', ['id' => $transaksi['id']]) ?>" class="btn btn-outline-warning rk-confirm-delete" data-confirm="Batalkan transaksi ini?"><i class="fa-solid fa-ban me-1"></i>Batalkan</a>
            <?php endif; ?>
            <a href="<?= Helper::url('transaksi/cetak', ['id' => $transaksi['id']]) ?>" class="btn btn-rk-amber" target="_blank"><i class="fa-solid fa-print me-1"></i>Cetak</a>
        </div>
    </div>
</div>

<div class="mt-2">
    <a href="<?= Helper::url('transaksi') ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
