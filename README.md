# Aplikasi Rental Kendaraan (PHP Native OOP + MySQL)

Proyek UAS Pemrograman Berorientasi Objek &mdash; Aplikasi Rental Kendaraan (mobil & motor)
dibangun dengan **PHP native** (tanpa framework), pola **MVC sederhana**, dan **MySQL**.

---

## 1. Fitur

- **Authentication**: Login, Logout, Session (role Admin & Petugas dengan menu berbeda)
- **Dashboard**: statistik kendaraan/pelanggan/transaksi + chart (Chart.js)
- **CRUD lengkap** (Tambah, Edit, Hapus, Detail) untuk 5 entitas: Kategori, Kendaraan, Pelanggan, Transaksi Sewa, Akun Pengguna
- **Relasi antar tabel**: kendaraan&rarr;kategori, transaksi&rarr;pelanggan, transaksi&rarr;kendaraan, transaksi&rarr;petugas
- **Validasi form** di semua input (required, email, numeric, min/max length)
- **Fitur tambahan** (lebih dari 2, sesuai catatan "poin ekstra" pada requirement):
  1. Search data (kategori, kendaraan, pelanggan, transaksi)
  2. Pagination (semua halaman list)
  3. Sorting data (kendaraan, pelanggan)
  4. Filter data (kendaraan by kategori/status, transaksi by status)
  5. Upload gambar (foto kendaraan)
  6. Export PDF sederhana (cetak struk transaksi via dialog print browser)
  7. Dashboard chart sederhana (status kendaraan + pendapatan bulanan)

---

## 2. Instalasi & Menjalankan

> **Persyaratan**: PHP **8.0 atau lebih baru** (kode memakai `match`, union types, dan
> typed properties) + MySQL/MariaDB + ekstensi `pdo_mysql` aktif.

### Opsi A &mdash; PHP Built-in Server (paling cepat, untuk uji coba)

```bash
# 1. Import database
mysql -u root -p < database/rental_kendaraan.sql

# 2. (Opsional) sesuaikan config/config.php jika kredensial DB Anda berbeda

# 3. Jalankan server bawaan PHP, document root harus folder public/
php -S localhost:8000 -t public

# 4. Buka di browser
http://localhost:8000
```

### Opsi B &mdash; XAMPP / Laragon

1. Copy folder project ini ke `htdocs/rental-kendaraan` (atau `www/`).
2. Import `database/rental_kendaraan.sql` lewat phpMyAdmin.
3. Buka `config/config.php`, ubah:
   ```php
   define('BASE_URL', '/rental-kendaraan/public');
   ```
4. Akses `http://localhost/rental-kendaraan/public`.

### Akun Login Default (sudah ada di seed data)

| Role    | Username | Password    |
|---------|----------|-------------|
| Admin   | `admin`  | `admin123`  |
| Petugas | `budi`   | `petugas123`|

---

## 3. Struktur Folder (Pola MVC)

```
rental-kendaraan/
├── app/
│   ├── Core/            -> Database, Model, Controller, Router, Session (kerangka MVC)
│   ├── Interfaces/      -> CRUDInterface, Sewable (ABSTRACTION)
│   ├── Helpers/         -> Helper, Validator (static utility class)
│   ├── Models/          -> User/Admin/Petugas, Kendaraan/Mobil/Motor, Customer, Kategori, TransaksiSewa
│   ├── Controllers/     -> Auth, Dashboard, Kategori, Kendaraan, Customer, Transaksi, User
│   └── autoload.php     -> autoloader sederhana (tanpa Composer)
├── config/config.php    -> konfigurasi DB & URL
├── public/
│   ├── index.php        -> Front Controller (satu pintu masuk seluruh request)
│   ├── .htaccess        -> rewrite rule (opsional, untuk Apache)
│   └── assets/          -> css, js, upload foto kendaraan
├── views/                -> seluruh tampilan (V pada MVC), dikelompokkan per modul
├── database/rental_kendaraan.sql
└── README.md
```

**Alur request (MVC):** `public/index.php` (Front Controller) &rarr; `App\Core\Router`
mencocokkan `?route=...` &rarr; memanggil method di `Controller` terkait &rarr; Controller
mengambil/mengubah data lewat `Model` &rarr; Controller merender `View` yang sesuai.

---

## 4. Pemetaan Requirement OOP ke Kode

| Requirement                  | Implementasi |
|-------------------------------|---------------|
| **Minimal 8 class**           | 25+ class/interface (lihat daftar di bawah) |
| **Encapsulation**              | Properti `private`/`protected` + getter publik di `User`, `Kendaraan`, `Mobil`, `Motor`, dll. Koneksi PDO di `Database` bersifat `private`. |
| **Inheritance (min. 2)**       | (1) `User` &rarr; `Admin`, `Petugas`. (2) `Kendaraan` &rarr; `Mobil`, `Motor`. Plus seluruh Model mewarisi `App\Core\Model`, seluruh Controller mewarisi `App\Core\Controller`. |
| **Polymorphism**                | `getMenuAkses()` (Admin vs Petugas), `hitungBiayaSewa()` & `getInfoSpesifik()` (Mobil vs Motor) &mdash; method overriding nyata dipakai di `TransaksiController` untuk menghitung biaya sewa. |
| **Abstraction**                 | Interface `CRUDInterface`, `Sewable`; abstract class `Model`, `Controller`, `User`, `Kendaraan`. |
| **Constructor di setiap class** | Semua class punya `__construct()` (langsung atau diwarisi dari induk). |
| **Static method (min. 1)**      | `Database::getInstance()`, `User::buatDariUsername()`, `User::verifikasiPassword()`, `KendaraanFactory::buat()`, `TransaksiSewa::generateKodeTransaksi()`, seluruh method `Helper` & `Validator`. |
| **Namespace**                    | `App\Core`, `App\Interfaces`, `App\Helpers`, `App\Models`, `App\Controllers`. |
| **Exception handling (min. 1)**  | try-catch di `Database` (koneksi gagal), `Model::create/update/delete` (constraint/duplicate), `Router::dispatch()` (pengaman global), seluruh Controller saat operasi simpan/hapus. |

### Daftar Class & Interface

```
App\Core\Database          (Singleton, static getInstance())
App\Core\Model              (abstract, implements CRUDInterface)
App\Core\Controller         (abstract)
App\Core\Router
App\Core\Session
App\Interfaces\CRUDInterface
App\Interfaces\Sewable
App\Helpers\Helper          (static utility)
App\Helpers\Validator       (static utility)
App\Models\User             (abstract, extends Model)
App\Models\Admin            (extends User)
App\Models\Petugas          (extends User)
App\Models\Kendaraan        (abstract, extends Model, implements Sewable)
App\Models\Mobil            (extends Kendaraan)
App\Models\Motor            (extends Kendaraan)
App\Models\KendaraanFactory (Factory Pattern, static method)
App\Models\Kategori         (extends Model)
App\Models\Customer         (extends Model)
App\Models\TransaksiSewa    (extends Model)
App\Controllers\AuthController        (extends Controller)
App\Controllers\DashboardController   (extends Controller)
App\Controllers\KategoriController    (extends Controller)
App\Controllers\KendaraanController   (extends Controller)
App\Controllers\CustomerController    (extends Controller)
App\Controllers\TransaksiController   (extends Controller)
App\Controllers\UserController        (extends Controller)
```

---

## 5. Catatan Desain Penting

- **Single Table Inheritance**: data Mobil & Motor disimpan dalam satu tabel `kendaraan`,
  dibedakan kolom `jenis`. `KendaraanFactory::buat($row)` akan otomatis membuat objek
  `Mobil` atau `Motor` yang tepat dari satu baris data &mdash; di sinilah polymorphism
  benar-benar dipakai: kode Controller/View tidak perlu tahu jenis konkretnya, cukup
  panggil `$kendaraan->hitungBiayaSewa($hari)`.
- **Export PDF sederhana** memakai halaman cetak (`transaksi/cetak`) yang dioptimalkan
  untuk dialog print browser (Ctrl+P &rarr; Save as PDF), tanpa dependency eksternal.
- **Reusable component**: seluruh operasi CRUD generik, pagination, dan search ditulis
  sekali di `App\Core\Model` dan diwarisi oleh semua Model anak (DRY).

---

## 6. Pengujian Manual yang Disarankan

1. Login sebagai `admin` &rarr; pastikan menu "Akun Pengguna" muncul.
2. Login sebagai `budi` &rarr; pastikan menu "Akun Pengguna" **tidak** muncul (role-based access).
3. Tambah kategori &rarr; tambah kendaraan (coba jenis Mobil & Motor, upload foto).
4. Tambah pelanggan baru.
5. Buat transaksi sewa &rarr; cek kendaraan otomatis berubah status jadi "Disewa".
6. Klik "Selesaikan" pada transaksi &rarr; cek kendaraan kembali "Tersedia" dan denda terhitung otomatis jika telat.
7. Coba hapus pelanggan/kendaraan yang masih punya transaksi &rarr; harus muncul pesan error (foreign key + try-catch bekerja).
8. Coba search, filter status, dan ganti halaman pagination di semua list.
9. Cetak struk transaksi (`transaksi/cetak`) lalu Save as PDF dari browser.

---

## 7. Lisensi

Dibuat untuk keperluan tugas akademik (UAS Pemrograman Berorientasi Objek).
