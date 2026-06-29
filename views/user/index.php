<?php

use App\Helpers\Helper;
use App\Core\Session;

$pageTitle = 'Akun Pengguna';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-end mb-3">
    <a href="<?= Helper::url('user/create') ?>" class="btn btn-rk-amber">
        <i class="fa-solid fa-plus me-1"></i> Tambah Akun
    </a>
</div>

<div class="rk-card p-0">
    <div class="table-responsive">
        <table class="table rk-table mb-0">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($userList)): ?>
                    <tr><td colspan="6" class="rk-empty">Belum ada akun.</td></tr>
                <?php else: ?>
                    <?php foreach ($userList as $i => $u): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($u['nama']) ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><span class="badge text-bg-<?= $u['role'] === 'admin' ? 'dark' : 'secondary' ?>"><?= ucfirst($u['role']) ?></span></td>
                            <td class="text-muted small"><?= Helper::formatTanggalIndo($u['created_at']) ?></td>
                            <td>
                                <a href="<?= Helper::url('user/edit', ['id' => $u['id']]) ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <?php if ((int) $u['id'] !== (int) Session::get('user_id')): ?>
                                    <a href="<?= Helper::url('user/delete', ['id' => $u['id']]) ?>"
                                       class="btn btn-sm btn-outline-danger rk-confirm-delete"
                                       data-confirm="Hapus akun '<?= htmlspecialchars($u['username']) ?>'?" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
