<?php

use App\Helpers\Helper;

$pageTitle = 'Edit Transaksi';
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:560px;">
    <h6 class="fw-bold mb-3">Edit Transaksi <?= htmlspecialchars($transaksi['kode_transaksi']) ?></h6>
    <p class="small text-muted">Tanggal sewa dan kendaraan tidak dapat diubah setelah transaksi dibuat. Total biaya akan dihitung ulang otomatis jika rencana tanggal kembali diubah.</p>

    <form method="POST" action="<?= Helper::url('transaksi/edit', ['id' => $transaksi['id']]) ?>">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Tanggal Sewa</label>
            <input type="text" class="form-control" value="<?= Helper::formatTanggalIndo($transaksi['tanggal_sewa']) ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Rencana Tanggal Kembali <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_kembali_rencana" class="form-control" value="<?= htmlspecialchars($transaksi['tanggal_kembali_rencana']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Catatan</label>
            <textarea name="catatan" class="form-control" rows="3"><?= htmlspecialchars($transaksi['catatan'] ?? '') ?></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-rk-primary">Simpan Perubahan</button>
            <a href="<?= Helper::url('transaksi') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
