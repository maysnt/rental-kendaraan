<?php

use App\Helpers\Helper;

$pageTitle = 'Data Pelanggan';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2" method="GET" action="">
        <input type="hidden" name="route" value="customer">
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="Cari nama, no HP, NIK..." style="width:280px;">
        <button class="btn btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <a href="<?= Helper::url('customer/create') ?>" class="btn btn-rk-amber">
        <i class="fa-solid fa-plus me-1"></i> Tambah Pelanggan
    </a>
</div>

<div class="rk-card p-0">
    <div class="table-responsive">
        <table class="table rk-table mb-0">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>
                        <a href="<?= Helper::url('customer', ['sort' => 'nama', 'direction' => $direction === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                            Nama <i class="fa-solid fa-sort small"></i>
                        </a>
                    </th>
                    <th>No. KTP</th>
                    <th>No. HP</th>
                    <th>Email</th>
                    <th style="width:160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customerList)): ?>
                    <tr><td colspan="6" class="rk-empty">Belum ada data pelanggan.</td></tr>
                <?php else: ?>
                    <?php foreach ($customerList as $i => $c): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($c['nama']) ?></td>
                            <td><?= htmlspecialchars($c['no_ktp']) ?></td>
                            <td><?= htmlspecialchars($c['no_hp']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars($c['email'] ?? '-') ?></td>
                            <td>
                                <a href="<?= Helper::url('customer/detail', ['id' => $c['id']]) ?>" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="<?= Helper::url('customer/edit', ['id' => $c['id']]) ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="<?= Helper::url('customer/delete', ['id' => $c['id']]) ?>"
                                   class="btn btn-sm btn-outline-danger rk-confirm-delete"
                                   data-confirm="Hapus pelanggan '<?= htmlspecialchars($c['nama']) ?>'?" title="Hapus">
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
                    <a class="page-link" href="<?= Helper::url('customer', ['page' => $p, 'q' => $keyword]) ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
