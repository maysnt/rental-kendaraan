<?php

use App\Helpers\Helper;

$pageTitle = 'Edit Kendaraan';
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:760px;">
    <h6 class="fw-bold mb-3">Form Edit Kendaraan</h6>
    <form method="POST" action="<?= Helper::url('kendaraan/edit', ['id' => $row['id']]) ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                <select name="jenis" id="select-jenis" class="form-select" required>
                    <option value="mobil" <?= $row['jenis'] === 'mobil' ? 'selected' : '' ?>>Mobil</option>
                    <option value="motor" <?= $row['jenis'] === 'motor' ? 'selected' : '' ?>>Motor</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Kategori</label>
                <select name="kategori_id" class="form-select">
                    <option value="0">- Tanpa Kategori -</option>
                    <?php foreach ($kategoriList as $kt): ?>
                        <option value="<?= $kt['id'] ?>" <?= (int) $row['kategori_id'] === (int) $kt['id'] ? 'selected' : '' ?>><?= htmlspecialchars($kt['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Kode Kendaraan</label>
                <input type="text" name="kode_kendaraan" class="form-control" value="<?= htmlspecialchars($row['kode_kendaraan']) ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Merk <span class="text-danger">*</span></label>
                <input type="text" name="merk" class="form-control" value="<?= htmlspecialchars($row['merk']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Model <span class="text-danger">*</span></label>
                <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($row['model']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tahun</label>
                <input type="number" name="tahun" class="form-control" min="1990" max="2030" value="<?= (int) $row['tahun'] ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
                <input type="text" name="plat_nomor" class="form-control" value="<?= htmlspecialchars($row['plat_nomor']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Harga Sewa / Hari (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga_sewa_harian" class="form-control" min="0" step="1000" value="<?= (float) $row['harga_sewa_harian'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="tersedia" <?= $row['status'] === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="disewa" <?= $row['status'] === 'disewa' ? 'selected' : '' ?>>Disewa</option>
                    <option value="maintenance" <?= $row['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                </select>
            </div>

            <div class="col-md-6 rk-field-mobil">
                <label class="form-label small fw-semibold">Kapasitas Penumpang</label>
                <input type="number" name="kapasitas_penumpang" class="form-control" min="1" value="<?= (int) ($row['kapasitas_penumpang'] ?? 0) ?>">
            </div>
            <div class="col-md-6 rk-field-mobil">
                <label class="form-label small fw-semibold">Transmisi</label>
                <select name="transmisi" class="form-select">
                    <option value="manual" <?= ($row['transmisi'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual</option>
                    <option value="otomatis" <?= ($row['transmisi'] ?? '') === 'otomatis' ? 'selected' : '' ?>>Otomatis</option>
                </select>
            </div>

            <div class="col-md-6 rk-field-motor">
                <label class="form-label small fw-semibold">Kapasitas CC</label>
                <input type="number" name="kapasitas_cc" class="form-control" min="50" value="<?= (int) ($row['kapasitas_cc'] ?? 0) ?>">
            </div>
            <div class="col-md-6 rk-field-motor">
                <label class="form-label small fw-semibold">Tipe Motor</label>
                <select name="tipe_motor" class="form-select">
                    <option value="matic" <?= ($row['tipe_motor'] ?? '') === 'matic' ? 'selected' : '' ?>>Matic</option>
                    <option value="manual" <?= ($row['tipe_motor'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual / Sport</option>
                </select>
            </div>

            <div class="col-md-8">
                <label class="form-label small fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($row['deskripsi'] ?? '') ?></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Foto Kendaraan</label>
                <?php if ($row['foto']): ?>
                    <img src="<?= Helper::asset(UPLOAD_URL . $row['foto']) ?>" class="rk-thumb-lg mb-2" style="max-height:120px;" alt="Foto saat ini">
                <?php endif; ?>
                <input type="file" name="foto" id="input-foto" accept=".jpg,.jpeg,.png,.webp" class="form-control">
                <img id="preview-foto" class="rk-thumb-lg mt-2 d-none" alt="Preview">
                <div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-rk-primary">Simpan Perubahan</button>
            <a href="<?= Helper::url('kendaraan') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function rkToggleJenisFields() {
    const isMotor = document.getElementById('select-jenis').value === 'motor';

    // Field Mobil
    document.querySelectorAll('.rk-field-mobil').forEach(function (group) {
        group.classList.toggle('d-none', isMotor);

        group.querySelectorAll('input, select').forEach(function (input) {
            input.disabled = isMotor;
        });
    });

    // Field Motor
    document.querySelectorAll('.rk-field-motor').forEach(function (group) {
        group.classList.toggle('d-none', !isMotor);

        group.querySelectorAll('input, select').forEach(function (input) {
            input.disabled = !isMotor;
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    rkToggleJenisFields();

    document.getElementById('select-jenis').addEventListener('change', rkToggleJenisFields);
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
