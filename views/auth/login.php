<?php

use App\Helpers\Helper;
use App\Core\Session;

$flashError   = Session::getFlash('error');
$flashSuccess = Session::getFlash('success');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &middot; Rental Kendaraan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="<?= Helper::asset('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<div class="rk-login-wrap">
    <div class="rk-login-side d-none d-md-flex">
        <div class="rk-brand-mark mb-4" style="width:54px;height:54px;font-size:1.2rem;">RK</div>
        <h1 class="rk-display" style="font-size:2.4rem;max-width:420px;">Kelola sewa kendaraan Anda dalam satu dashboard.</h1>
        <p class="opacity-75" style="max-width:420px;">
            Pantau ketersediaan unit, kelola pelanggan, dan catat transaksi sewa
            mobil &amp; motor secara rapi dan terpusat.
        </p>
        <div class="d-flex gap-4 mt-4">
            <div>
                <div class="fs-4 fw-bold">Cepat</div>
                <div class="small opacity-75">Catat transaksi dalam hitungan detik</div>
            </div>
            <div>
                <div class="fs-4 fw-bold">Rapi</div>
                <div class="small opacity-75">Riwayat sewa tersimpan otomatis</div>
            </div>
        </div>
    </div>

    <div class="rk-login-form-wrap">
        <div style="width:100%;max-width:340px;">
            <h2 class="mb-1">Selamat Datang</h2>
            <p class="text-muted mb-4">Masuk untuk mengakses sistem rental kendaraan.</p>

            <?php if ($flashError): ?>
                <div class="alert alert-danger small"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>
            <?php if ($flashSuccess): ?>
                <div class="alert alert-success small"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= Helper::url('login') ?>">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="cth: admin" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-rk-amber w-100 py-2">Masuk</button>
            </form>

            <div class="mt-4 small text-muted">
                <strong>Akun demo:</strong><br>
                Admin &mdash; admin / admin123<br>
                Petugas &mdash; budi / petugas123
            </div>
        </div>
    </div>
</div>
</body>
</html>
