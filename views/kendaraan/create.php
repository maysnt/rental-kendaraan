<?php

use App\Helpers\Helper;

$pageTitle = 'Tambah Kendaraan';
require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-4" style="max-width:760px;">
    <h6 class="fw-bold mb-3">Form Tambah Kendaraan</h6>
    <form method="POST" action="<?= Helper::url('kendaraan/create') ?>" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
                <select name="jenis" id="select-jenis" class="form-select" required>
                    <option value="mobil">Mobil</option>
                    <option value="motor">Motor</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Kategori</label>
                <select name="kategori_id" class="form-select">
                    <option value="0">- Tanpa Kategori -</option>
                    <?php foreach ($kategoriList as $kt): ?>
                        <option value="<?= $kt['id'] ?>"><?= htmlspecialchars($kt['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Kode Kendaraan</label>
                <input type="text" name="kode_kendaraan" class="form-control" placeholder="Otomatis jika dikosongkan">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Merk <span class="text-danger">*</span></label>
                <input type="text" name="merk" class="form-control" placeholder="cth: Toyota / Honda" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Model <span class="text-danger">*</span></label>
                <input type="text" name="model" class="form-control" placeholder="cth: Avanza / Vario" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tahun</label>
                <input type="number" name="tahun" class="form-control" min="1990" max="2030" value="<?= date('Y') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
                <input type="text" name="plat_nomor" class="form-control" placeholder="cth: B 1234 ABC" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Harga Sewa / Hari (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="harga_sewa_harian" class="form-control" min="0" step="1000" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="tersedia">Tersedia</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="col-md-6 rk-field-mobil">
                <label class="form-label small fw-semibold">Kapasitas Penumpang</label>
                <input type="number" name="kapasitas_penumpang" class="form-control" min="1" placeholder="cth: 6">
            </div>
            <div class="col-md-6 rk-field-mobil">
                <label class="form-label small fw-semibold">Transmisi</label>
                <select name="transmisi" class="form-select">
                    <option value="manual">Manual</option>
                    <option value="otomatis">Otomatis</option>
                </select>
            </div>

            <div class="col-md-6 rk-field-motor d-none">
                <label class="form-label small fw-semibold">Kapasitas CC</label>
                <input type="number" name="kapasitas_cc" class="form-control" min="50" placeholder="cth: 150">
            </div>
            <div class="col-md-6 rk-field-motor d-none">
                <label class="form-label small fw-semibold">Tipe Motor</label>
                <select name="tipe_motor" class="form-select">
                    <option value="matic">Matic</option>
                    <option value="manual">Manual / Sport</option>
                </select>
            </div>

            <div class="col-md-8">
                <label class="form-label small fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Foto Kendaraan</label>
                <input type="file" name="foto" id="input-foto" accept=".jpg,.jpeg,.png,.webp" class="form-control">
                <img id="preview-foto" class="rk-thumb-lg mt-2 d-none" alt="Preview">
                <div class="form-text">Maks 2MB (jpg/png/webp)</div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-rk-primary">Simpan Kendaraan</button>
            <a href="<?= Helper::url('kendaraan') ?>" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('select-jenis').addEventListener('change', function () {
    var isMotor = this.value === 'motor';
    document.querySelectorAll('.rk-field-mobil').forEach(function (el) { el.classList.toggle('d-none', isMotor); });
    document.querySelectorAll('.rk-field-motor').forEach(function (el) { el.classList.toggle('d-none', !isMotor); });
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
