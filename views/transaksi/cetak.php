<?php

use App\Helpers\Helper;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk <?= htmlspecialchars($transaksi['kode_transaksi']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f6fa; font-family: 'Segoe UI', sans-serif; }
        .struk { max-width: 520px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 10px; }
        .struk hr { border-top: 1px dashed #ccc; }
        .row-line { display: flex; justify-content: space-between; font-size: 0.92rem; margin-bottom: 6px; }
        @media print {
            body { background: #fff; }
            .rk-no-print { display: none !important; }
            .struk { margin: 0; box-shadow: none; }
        }
    </style>
</head>
<body>
<div class="struk">
    <div class="text-center mb-3">
        <h5 class="fw-bold mb-0">RENTAL KENDARAAN</h5>
        <div class="small text-muted">Struk Transaksi Sewa</div>
    </div>
    <hr>
    <div class="row-line"><span>Kode Transaksi</span><strong><?= htmlspecialchars($transaksi['kode_transaksi']) ?></strong></div>
    <div class="row-line"><span>Tanggal Transaksi</span><span><?= Helper::formatTanggalIndo($transaksi['created_at']) ?></span></div>
    <div class="row-line"><span>Status</span><span><?= ucfirst($transaksi['status']) ?></span></div>
    <hr>
    <div class="row-line"><span>Pelanggan</span><span><?= htmlspecialchars($transaksi['nama_customer']) ?></span></div>
    <div class="row-line"><span>No. HP</span><span><?= htmlspecialchars($transaksi['no_hp']) ?></span></div>
    <hr>
    <div class="row-line"><span>Kendaraan</span><span><?= htmlspecialchars($transaksi['merk'] . ' ' . $transaksi['model']) ?></span></div>
    <div class="row-line"><span>Plat Nomor</span><span><?= htmlspecialchars($transaksi['plat_nomor']) ?></span></div>
    <div class="row-line"><span>Tanggal Sewa</span><span><?= Helper::formatTanggalIndo($transaksi['tanggal_sewa']) ?></span></div>
    <div class="row-line"><span>Rencana Kembali</span><span><?= Helper::formatTanggalIndo($transaksi['tanggal_kembali_rencana']) ?></span></div>
    <div class="row-line"><span>Lama Sewa</span><span><?= (int) $transaksi['lama_sewa'] ?> hari</span></div>
    <hr>
    <div class="row-line"><span>Biaya Sewa</span><span><?= Helper::formatRupiah($transaksi['total_biaya']) ?></span></div>
    <div class="row-line"><span>Denda</span><span><?= Helper::formatRupiah($transaksi['denda']) ?></span></div>
    <div class="row-line fw-bold fs-5"><span>TOTAL</span><span><?= Helper::formatRupiah((float) $transaksi['total_biaya'] + (float) $transaksi['denda']) ?></span></div>
    <hr>
    <p class="text-center small text-muted mb-0">Terima kasih telah menggunakan layanan kami.</p>

    <div class="text-center mt-4 rk-no-print">
        <button class="btn btn-dark" onclick="window.print()">Cetak / Simpan sebagai PDF</button>
    </div>
</div>
</body>
</html>
