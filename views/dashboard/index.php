<?php

use App\Helpers\Helper;

$pageTitle = 'Dashboard';
require __DIR__ . '/../layouts/header.php';
?>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="rk-stat">
            <div>
                <div class="rk-stat-value"><?= (int) $totalKendaraan ?></div>
                <div class="rk-stat-label">Total Kendaraan</div>
            </div>
            <div class="rk-stat-icon rk-icon-navy"><i class="fa-solid fa-car-side"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="rk-stat">
            <div>
                <div class="rk-stat-value"><?= (int) $totalCustomer ?></div>
                <div class="rk-stat-label">Total Pelanggan</div>
            </div>
            <div class="rk-stat-icon rk-icon-amber"><i class="fa-solid fa-users"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="rk-stat">
            <div>
                <div class="rk-stat-value"><?= (int) $transaksiAktif ?></div>
                <div class="rk-stat-label">Transaksi Berjalan</div>
            </div>
            <div class="rk-stat-icon rk-icon-green"><i class="fa-solid fa-receipt"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="rk-stat">
            <div>
                <div class="rk-stat-value" style="font-size:1.25rem;"><?= Helper::formatRupiah($pendapatanBulanIni) ?></div>
                <div class="rk-stat-label">Pendapatan Bulan Ini</div>
            </div>
            <div class="rk-stat-icon rk-icon-red"><i class="fa-solid fa-coins"></i></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="rk-card p-4 h-100">
            <h6 class="fw-bold mb-3">Status Kendaraan</h6>
            <canvas id="chartStatus" height="220"></canvas>
            <div class="d-flex justify-content-between small text-muted mt-3">
                <span><i class="fa-solid fa-circle text-success me-1"></i>Tersedia: <?= (int) $kendaraanTersedia ?></span>
                <span><i class="fa-solid fa-circle text-warning me-1"></i>Disewa: <?= (int) $kendaraanDisewa ?></span>
                <span><i class="fa-solid fa-circle text-secondary me-1"></i>Maintenance: <?= (int) $kendaraanMaintenance ?></span>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="rk-card p-4 h-100">
            <h6 class="fw-bold mb-3">Pendapatan 6 Bulan Terakhir (Transaksi Selesai)</h6>
            <canvas id="chartPendapatan" height="220"></canvas>
        </div>
    </div>
</div>

<div class="rk-card p-0">
    <div class="p-3 px-4 border-bottom">
        <h6 class="fw-bold mb-0">Transaksi Terbaru</h6>
    </div>
    <div class="table-responsive">
        <table class="table rk-table mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Status</th>
                    <th>Total Biaya</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transaksiTerbaru)): ?>
                    <tr><td colspan="5" class="rk-empty">Belum ada transaksi.</td></tr>
                <?php else: ?>
                    <?php foreach ($transaksiTerbaru as $t): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($t['kode_transaksi']) ?></td>
                            <td><?= htmlspecialchars($t['nama_customer']) ?></td>
                            <td><?= htmlspecialchars($t['merk'] . ' ' . $t['model']) ?></td>
                            <td><?= Helper::statusBadge($t['status']) ?></td>
                            <td><?= Helper::formatRupiah($t['total_biaya']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartStatus'), {
    type: 'doughnut',
    data: {
        labels: ['Tersedia', 'Disewa', 'Maintenance'],
        datasets: [{
            data: [<?= (int) $kendaraanTersedia ?>, <?= (int) $kendaraanDisewa ?>, <?= (int) $kendaraanMaintenance ?>],
            backgroundColor: ['#22a963', '#f2a93b', '#9aa3b8'],
            borderWidth: 0
        }]
    },
    options: { plugins: { legend: { display: false } }, cutout: '68%' }
});

new Chart(document.getElementById('chartPendapatan'), {
    type: 'bar',
    data: {
        labels: [<?php foreach ($statistikBulanan as $s) { echo "'" . htmlspecialchars($s['bulan']) . "',"; } ?>],
        datasets: [{
            label: 'Pendapatan',
            data: [<?php foreach ($statistikBulanan as $s) { echo (float) $s['total'] . ','; } ?>],
            backgroundColor: '#16223d',
            borderRadius: 6,
            maxBarThickness: 42
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: function (v) { return 'Rp ' + v.toLocaleString('id-ID'); } } } }
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
