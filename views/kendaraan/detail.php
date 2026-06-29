<?php

use App\Helpers\Helper;

$pageTitle = 'Detail Kendaraan';
require __DIR__ . '/../layouts/header.php';
?>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="rk-card overflow-hidden">
            <?php if ($kendaraan->getFoto()): ?>
                <img src="<?= BASE_URL . '/' . UPLOAD_URL . $kendaraan->getFoto() ?>" class="w-100" alt="Foto Kendaraan" style="height:220px;object-fit:cover;">
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center bg-light" style="height:220px;color:#9aa3b8;">
                    <i class="fa-solid fa-car-side fa-3x"></i>
                </div>
            <?php endif; ?>
            <div class="p-3">
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($kendaraan->getNamaLengkap()) ?></h5>
                <div class="mb-2"><?= Helper::statusBadge($kendaraan->getStatus()) ?></div>
                <div class="small text-muted">Kode: <?= htmlspecialchars($kendaraan->getKodeKendaraan()) ?></div>
                <div class="small text-muted">Plat: <?= htmlspecialchars($kendaraan->getPlatNomor()) ?></div>
                <div class="small text-muted">Kategori: <?= htmlspecialchars($kategori['nama_kategori'] ?? 'Tanpa kategori') ?></div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="rk-card p-4 mb-3">
            <h6 class="fw-bold mb-3">Spesifikasi</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Jenis</div>
                    <div class="fw-semibold"><?= $row['jenis'] === 'motor' ? 'Motor' : 'Mobil' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Info Spesifik</div>
                    <div class="fw-semibold"><?= htmlspecialchars($kendaraan->getInfoSpesifik()) ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Harga Sewa Harian</div>
                    <div class="fw-semibold"><?= Helper::formatRupiah($kendaraan->getHargaSewaHarian()) ?></div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Tahun</div>
                    <div class="fw-semibold"><?= $kendaraan->getTahun() ?: '-' ?></div>
                </div>
                <?php if ($kendaraan->getDeskripsi()): ?>
                <div class="col-12">
                    <div class="small text-muted">Deskripsi</div>
                    <div><?= nl2br(htmlspecialchars($kendaraan->getDeskripsi())) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="rk-card p-4">
            <h6 class="fw-bold mb-1">Simulasi Biaya Sewa</h6>
            <p class="small text-muted mb-3">
                Estimasi total biaya sewa berdasarkan jenis kendaraan dan lama penyewaan. Perhitungan sudah memperhitungkan aturan biaya tambahan atau diskon sesuai jenis kendaraan.
            </p>
            <div class="row g-2">
                <?php foreach ([1, 3, 5, 7] as $hari): ?>
                    <div class="col-md-3 col-6">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="small text-muted"><?= $hari ?> Hari</div>
                            <div class="fw-bold"><?= Helper::formatRupiah($kendaraan->hitungBiayaSewa($hari)) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="<?= Helper::url('kendaraan') ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
    <a href="<?= Helper::url('kendaraan/edit', ['id' => $kendaraan->getId()]) ?>" class="btn btn-rk-primary">
        <i class="fa-solid fa-pen me-1"></i> Edit
    </a>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
