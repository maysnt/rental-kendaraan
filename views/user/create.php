<?php

use App\Helpers\Helper;

$pageTitle = 'Tambah Akun';
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:520px;">
    <h6 class="fw-bold mb-3">Form Tambah Akun</h6>
    <form method="POST" action="<?= Helper::url('user/create') ?>">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" minlength="6" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select">
                <option value="petugas">Petugas</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-rk-primary">Simpan</button>
            <a href="<?= Helper::url('user') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
