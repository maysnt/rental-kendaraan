<?php

use App\Helpers\Helper;

$pageTitle = 'Data Kendaraan';

$kategoriMap = [];
foreach ($kategoriList as $kt) {
    $kategoriMap[$kt['id']] = $kt['nama_kategori'];
}

require __DIR__ . '/../layouts/header.php';
?>

<div class="rk-card p-3 mb-3">
    <form method="GET" action="" class="row g-2 align-items-center">
        <input type="hidden" name="route" value="kendaraan">
        <div class="col-md-3">
            <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="Cari merk, model, plat nomor...">
        </div>
        <div class="col-md-2">
            <select name="kategori_id" class="form-select">
                <option value="0">Semua Kategori</option>
                <?php foreach ($kategoriList as $kt): ?>
                    <option value="<?= $kt['id'] ?>" <?= $kategoriId === (int) $kt['id'] ? 'selected' : '' ?>><?= htmlspecialchars($kt['nama_kategori']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="tersedia" <?= $status === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="disewa" <?= $status === 'disewa' ? 'selected' : '' ?>>Disewa</option>
                <option value="maintenance" <?= $status === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="id" <?= $sort === 'id' ? 'selected' : '' ?>>Terbaru</option>
                <option value="merk" <?= $sort === 'merk' ? 'selected' : '' ?>>Nama (Merk)</option>
                <option value="harga_sewa_harian" <?= $sort === 'harga_sewa_harian' ? 'selected' : '' ?>>Harga Sewa</option>
                <option value="tahun" <?= $sort === 'tahun' ? 'selected' : '' ?>>Tahun</option>
            </select>
        </div>
        <div class="col-md-1">
            <select name="direction" class="form-select">
                <option value="DESC" <?= $direction === 'DESC' ? 'selected' : '' ?>>&#9660;</option>
                <option value="ASC" <?= $direction === 'ASC' ? 'selected' : '' ?>>&#9650;</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-outline-secondary flex-fill"><i class="fa-solid fa-filter me-1"></i>Terapkan</button>
        </div>
    </form>
</div>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= Helper::url('kendaraan/create') ?>" class="btn btn-rk-amber">
        <i class="fa-solid fa-plus me-1"></i> Tambah Kendaraan
    </a>
</div>

<div class="row g-3">
    <?php if (empty($kendaraanList)): ?>
        <div class="col-12"><div class="rk-card rk-empty">Tidak ada data kendaraan yang cocok.</div></div>
    <?php else: ?>
        <?php foreach ($kendaraanList as $kendaraan): ?>
            <div class="col-lg-4 col-md-6">
                <div class="rk-card h-100 overflow-hidden">
                    <?php if ($kendaraan->getFoto()): ?>
                        <img src="<?= Helper::asset(UPLOAD_URL . $kendaraan->getFoto()) ?>" class="w-100" style="height:170px;object-fit:cover;" alt="Foto kendaraan">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:170px;color:#9aa3b8;">
                            <i class="fa-solid fa-car-side fa-2x"></i>
                        </div>
                    <?php endif; ?>
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($kendaraan->getNamaLengkap()) ?></h6>
                            <?= Helper::statusBadge($kendaraan->getStatus()) ?>
                        </div>
                        <div class="text-muted small mb-1">
                            <i class="fa-solid fa-id-card me-1"></i><?= htmlspecialchars($kendaraan->getPlatNomor()) ?>
                            &middot; <?= htmlspecialchars($kategoriMap[$kendaraan->getKategoriId()] ?? 'Tanpa kategori') ?>
                        </div>
                        <div class="text-muted small mb-2"><?= htmlspecialchars($kendaraan->getInfoSpesifik()) ?></div>
                        <div class="fw-bold mb-3"><?= Helper::formatRupiah($kendaraan->getHargaSewaHarian()) ?> <span class="text-muted small fw-normal">/ hari</span></div>
                        <div class="d-flex gap-2">
                            <a href="<?= Helper::url('kendaraan/detail', ['id' => $kendaraan->getId()]) ?>" class="btn btn-sm btn-outline-secondary flex-fill">Detail</a>
                            <a href="<?= Helper::url('kendaraan/edit', ['id' => $kendaraan->getId()]) ?>" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-pen"></i></a>
                            <a href="<?= Helper::url('kendaraan/delete', ['id' => $kendaraan->getId()]) ?>"
                               class="btn btn-sm btn-outline-danger rk-confirm-delete"
                               data-confirm="Hapus kendaraan ini?"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if ($totalPage > 1): ?>
    <nav class="mt-4">
        <ul class="pagination rk-pagination">
            <?php for ($p = 1; $p <= $totalPage; $p++): ?>
                <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= Helper::url('kendaraan', ['page' => $p, 'q' => $keyword, 'kategori_id' => $kategoriId, 'status' => $status, 'sort' => $sort, 'direction' => $direction]) ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
