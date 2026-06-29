<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>500 - Terjadi Kesalahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;background:#f5f6fa;">
    <div class="text-center" style="max-width:560px;">
        <h1 class="display-3 fw-bold">500</h1>
        <p class="text-muted mb-2">Terjadi kesalahan pada server saat memproses permintaan Anda.</p>
        <?php if (!empty($errorMessage)): ?>
            <p class="small text-danger"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-dark mt-3">Kembali ke Beranda</a>
    </div>
</body>
</html>
