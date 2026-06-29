<?php

use App\Helpers\Helper;

$pageTitle = 'Buat Transaksi Sewa';
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:680px;">
    <h6 class="fw-bold mb-3">Form Transaksi Sewa Baru</h6>

    <?php if (empty($kendaraanList)): ?>
        <div class="alert alert-warning small">Tidak ada kendaraan yang tersedia untuk disewa saat ini.</div>
    <?php endif; ?>

    <form method="POST" action="<?= Helper::url('transaksi/create') ?>">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label small fw-semibold">Pelanggan <span class="text-danger">*</span></label>
                <select name="customer_id" class="form-select" required>
                    <option value="">- Pilih Pelanggan -</option>
                    <?php foreach ($customerList as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nama']) ?> &middot; <?= htmlspecialchars($c['no_hp']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Pelanggan belum terdaftar? <a href="<?= Helper::url('customer/create') ?>">Tambah pelanggan baru</a>.</div>
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Kendaraan <span class="text-danger">*</span></label>
                <select name="kendaraan_id" class="form-select" required <?= empty($kendaraanList) ? 'disabled' : '' ?>>
                    <option value="">- Pilih Kendaraan Tersedia -</option>
                    <?php foreach ($kendaraanList as $k): ?>
                        <option value="<?= $k->getId() ?>">
                            <?= htmlspecialchars($k->getNamaLengkap()) ?> &middot; <?= htmlspecialchars($k->getPlatNomor()) ?> &middot; <?= Helper::formatRupiah($k->getHargaSewaHarian()) ?>/hari
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Tanggal Sewa <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_sewa" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Rencana Tanggal Kembali <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_kembali_rencana" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Opsional"></textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-rk-primary" <?= empty($kendaraanList) ? 'disabled' : '' ?>>Buat Transaksi</button>
            <a href="<?= Helper::url('transaksi') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
