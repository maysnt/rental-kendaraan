<?php

use App\Helpers\Helper;

$pageTitle = 'Edit Akun';
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:520px;">
    <h6 class="fw-bold mb-3">Form Edit Akun</h6>
    <form method="POST" action="<?= Helper::url('user/edit', ['id' => $user['id']]) ?>">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" minlength="6" placeholder="Kosongkan jika tidak ingin mengubah">
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select">
                <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-rk-primary">Simpan Perubahan</button>
            <a href="<?= Helper::url('user') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
