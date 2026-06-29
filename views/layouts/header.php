<?php

use App\Helpers\Helper;
use App\Core\Session;

$pageTitle    = $pageTitle ?? 'Dashboard';
$flashSuccess = Session::getFlash('success');
$flashError   = Session::getFlash('error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> &middot; Rental Kendaraan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/rental-kendaraan/public/assets/css/style.css">

</head>
<body>
<div class="rk-shell">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="rk-main">
        <div class="rk-topbar rk-no-print">
            <div class="rk-topbar-title"><?= htmlspecialchars($pageTitle) ?></div>
            <div class="rk-user-chip">
                <div class="rk-avatar"><?= htmlspecialchars(strtoupper(substr((string) Session::get('nama', 'U'), 0, 1))) ?></div>
                <div>
                    <div class="fw-semibold"><?= htmlspecialchars((string) Session::get('nama', '')) ?></div>
                    <div class="text-muted small"><?= htmlspecialchars(ucfirst((string) Session::get('role', ''))) ?></div>
                </div>
                <a href="<?= Helper::url('logout') ?>" class="btn btn-sm btn-outline-danger ms-2" title="Keluar">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
        </div>

        <div class="rk-content">
            <?php if ($flashSuccess): ?>
                <div class="alert alert-success rk-alert-auto rk-no-print" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                </div>
            <?php endif; ?>
            <?php if ($flashError): ?>
                <div class="alert alert-danger rk-alert-auto rk-no-print" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($flashError) ?>
                </div>
            <?php endif; ?>
