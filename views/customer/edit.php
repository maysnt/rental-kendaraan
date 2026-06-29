<?php

use App\Helpers\Helper;

$pageTitle = 'Edit Pelanggan';
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:640px;">
    <h6 class="fw-bold mb-3">Form Edit Pelanggan</h6>
    <form method="POST" action="<?= Helper::url('customer/edit', ['id' => $customer['id']]) ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($customer['nama']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">No. KTP (16 digit) <span class="text-danger">*</span></label>
                <input type="text" name="no_ktp" class="form-control" maxlength="16" minlength="16" value="<?= htmlspecialchars($customer['no_ktp']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">No. HP <span class="text-danger">*</span></label>
                <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($customer['no_hp']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"><?= htmlspecialchars($customer['alamat'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-rk-primary">Simpan Perubahan</button>
            <a href="<?= Helper::url('customer') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
