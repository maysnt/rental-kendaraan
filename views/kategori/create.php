<?php

use App\Helpers\Helper;

$pageTitle = 'Tambah Kategori';
$oldNama = Helper::old('nama_kategori');
$oldDeskripsi = Helper::old('deskripsi');
Helper::clearOld();
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:560px;">
    <h6 class="fw-bold mb-3">Form Tambah Kategori</h6>
    <form method="POST" action="<?= Helper::url('kategori/create') ?>">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars((string) $oldNama) ?>" placeholder="cth: City Car, MPV, Matic" required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat (opsional)"><?= htmlspecialchars((string) $oldDeskripsi) ?></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-rk-primary">Simpan</button>
            <a href="<?= Helper::url('kategori') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
