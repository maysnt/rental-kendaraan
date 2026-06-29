<?php

use App\Helpers\Helper;

$pageTitle = 'Kategori Kendaraan';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET" action="">
        <input type="hidden" name="route" value="kategori">
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="Cari nama kategori..." style="width:260px;">
        <button class="btn btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <a href="<?= Helper::url('kategori/create') ?>" class="btn btn-rk-amber">
        <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
    </a>
</div>

<div class="rk-card p-0">
    <div class="table-responsive">
        <table class="table rk-table mb-0">
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kategoriList)): ?>
                    <tr><td colspan="4" class="rk-empty">Belum ada data kategori.</td></tr>
                <?php else: ?>
                    <?php foreach ($kategoriList as $i => $k): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($k['nama_kategori']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($k['deskripsi'] ?? '-') ?></td>
                            <td>
                                <a href="<?= Helper::url('kategori/edit', ['id' => $k['id']]) ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="<?= Helper::url('kategori/delete', ['id' => $k['id']]) ?>"
                                   class="btn btn-sm btn-outline-danger rk-confirm-delete"
                                   data-confirm="Hapus kategori '<?= htmlspecialchars($k['nama_kategori']) ?>'?" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPage > 1): ?>
    <nav class="mt-3">
        <ul class="pagination rk-pagination">
            <?php for ($p = 1; $p <= $totalPage; $p++): ?>
                <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= Helper::url('kategori', ['page' => $p, 'q' => $keyword]) ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
