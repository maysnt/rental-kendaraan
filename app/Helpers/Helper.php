<?php

namespace App\Helpers;

/**
 * Helper
 *
 * Kumpulan fungsi bantu yang dipanggil secara statis dari Controller & View.
 * STATIC METHOD: seluruh method di class ini static (memenuhi requirement minimal 1 static method,
 * sekaligus best practice untuk utility class yang tidak butuh state).
 */
class Helper
{
    /**
     * Membangun URL berbasis query string ?route=...
     */
    public static function url(string $route = '', array $params = []): string
    {
        $base  = defined('BASE_URL') ? BASE_URL : '';
        $query = 'route=' . $route;

        foreach ($params as $key => $value) {
            $query .= '&' . $key . '=' . urlencode((string) $value);
        }

        return $base . '/index.php?' . $query;
    }

    /**
     * Membangun URL untuk file statis (css/js/gambar) di folder public/.
     */
    public static function asset(string $path): string
    {
        $base = defined('BASE_URL') ? BASE_URL : '';
        return $base . '/' . ltrim($path, '/');
    }

    public static function formatRupiah(float|int|string $angka): string
    {
        return 'Rp ' . number_format((float) $angka, 0, ',', '.');
    }

    public static function formatTanggalIndo(?string $tanggal): string
    {
        if (empty($tanggal)) {
            return '-';
        }

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $timestamp = strtotime($tanggal);
        if ($timestamp === false) {
            return $tanggal;
        }

        return (int) date('d', $timestamp) . ' ' . $bulan[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }

    public static function statusBadge(string $status): string
    {
        $map = [
            'tersedia'    => 'success',
            'disewa'      => 'warning',
            'maintenance' => 'secondary',
            'booking'     => 'info',
            'berjalan'    => 'warning',
            'selesai'     => 'success',
            'batal'       => 'danger',
        ];

        $kelas = $map[$status] ?? 'secondary';
        return '<span class="badge text-bg-' . $kelas . '">' . ucfirst($status) . '</span>';
    }

    /**
     * Upload foto kendaraan dengan validasi ekstensi & ukuran file.
     * Melempar RuntimeException jika gagal (ditangkap oleh try-catch di Controller).
     */
    public static function uploadFoto(array $file, string $uploadDir, array $allowedExt = ['jpg', 'jpeg', 'png', 'webp']): string|false
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return false;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload gagal dengan kode error: ' . $file['error']);
        }

        $ext = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            throw new \RuntimeException('Tipe file tidak diizinkan. Gunakan: ' . implode(', ', $allowedExt));
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            throw new \RuntimeException('Ukuran file maksimal 2MB.');
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $namaFile = 'kendaraan_' . uniqid() . '.' . $ext;
        $tujuan   = rtrim($uploadDir, '/') . '/' . $namaFile;

        if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
            throw new \RuntimeException('Gagal memindahkan file upload ke folder tujuan.');
        }

        return $namaFile;
    }

    public static function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['old'][$key] ?? $default;
    }

    public static function clearOld(): void
    {
        unset($_SESSION['old']);
    }

    public static function generateKodeUnik(string $prefix): string
    {
        return strtoupper($prefix) . date('Ymd') . strtoupper(substr(uniqid(), -5));
    }

    /**
     * Menentukan apakah sebuah menu sidebar harus ditandai "active"
     * berdasarkan route yang sedang dibuka.
     */
    public static function isActiveRoute(string $needle, string $current): bool
    {
        return $current === $needle || str_starts_with($current, $needle . '/');
    }
}
