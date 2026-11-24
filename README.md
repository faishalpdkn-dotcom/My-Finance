# My Finance - Aplikasi Pencatatan Keuangan Pribadi

Tugas Besar Implementasi Perangkat Lunak (IPL) - Kelompok 2
Program Studi Teknik Informatika, Universitas Jenderal Achmad Yani (2026).

Implementasi dari dokumen **SRS** dan **SDD** My Finance menggunakan **Laravel 12** (PHP, Blade, MySQL/SQLite).

| NIM | Nama |
|---|---|
| 2450081072 | Kevin Cahyawiguna |
| 2450081074 | Muchammad Rizal Sobari |
| 2450081077 | Muh. Zacky Yasir Syifa |
| 2450081080 | Muhammad Farielyabi Ghani |
| 2450081089 | Faishal Akbar Widyatna |

## Fitur (sesuai SRS F01-F10)

| Fitur | Lokasi kode utama |
|---|---|
| Registrasi, login, logout (F09) | `app/Http/Controllers/Auth/*`, `resources/views/auth/*` |
| Dashboard keuangan per bulan (F01): pilih bulan, angka hanya bulan terpilih | `DashboardController`, `resources/views/dashboard.blade.php` |
| Pencatatan pemasukan & pengeluaran (F02, F03) | `TransactionController`, `TransactionRequest` |
| Riwayat, edit, hapus, filter transaksi (F04-F06) | `TransactionController`, `resources/views/transactions/*` |
| Laporan keuangan + grafik bulanan (F07) | `ReportController`, `app/Services/ReportService.php` |
| Anggaran bulanan (F08) | `BudgetController`, `app/Models/Budget.php` |
| Target tabungan (F10): setoran mengurangi saldo (dicatat sebagai pengeluaran kategori Tabungan) | `SavingsGoalController`, `app/Models/SavingsGoal.php` |
| Kategori transaksi per pengguna | `CategoryController` |

Aturan keamanan dari SRS/SDD yang diterapkan: password di-hash, semua halaman pribadi wajib login, setiap operasi data memeriksa kepemilikan (403 jika bukan milik pengguna), query memakai Eloquent (parameterized), input divalidasi, tampilan berbahasa Indonesia dengan format Rupiah dan responsif (sidebar desktop, menu ringkas di mobile).

## Kebutuhan

- PHP 8.2+ (ekstensi: `pdo_sqlite` atau `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`)
- Composer 2
- Tidak perlu Node.js / npm (CSS dan JS sudah ada di `public/`)

## Menjalankan (cara cepat, SQLite)

```bash
composer setup     # install, buat .env, buat database sqlite, generate key, migrate + seed
composer dev       # atau: php artisan serve
```

Buka http://localhost:8000

Akun demo (dari seeder): **demo@myfinance.test** / **password123**

### Cara manual

```bash
composer install
cp .env.example .env            # Windows: copy .env.example .env
touch database/database.sqlite  # Windows: type nul > database\database.sqlite
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Memakai MySQL (XAMPP)

1. Jalankan Apache dan MySQL di XAMPP, lalu buat database kosong `my_finance` lewat phpMyAdmin.
2. Di `.env`, ganti `DB_CONNECTION=sqlite` dengan:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_finance
DB_USERNAME=root
DB_PASSWORD=
```

3. Jalankan `php artisan migrate --seed`.

## Pengujian

```bash
composer test      # atau: php artisan test
```

Mencakup autentikasi, CRUD transaksi, validasi, kepemilikan data, filter, laporan, anggaran, dan target tabungan.

## Struktur database (ERD pada SDD Bab IV)

`users` 1-N `categories`, `transactions`, `budgets`, `savings_goals`; `categories` 1-N `transactions`, `budgets`.
Nominal memakai `DECIMAL(15,2)`; periode anggaran disimpan sebagai `YYYY-MM`.

## Panduan Git: commit satu per satu tiap anggota

Repo ini sudah berisi versi lengkap. Agar riwayat commit mencerminkan kontribusi tiap orang, salah satu cara adalah pembagian berikut (urutan commit penting karena ada ketergantungan):

| Urutan | Anggota (usulan) | Isi commit | File utama |
|---|---|---|---|
| 1 | Ketua / siapa saja | Inisialisasi project Laravel, konfigurasi | `composer.json`, `.env.example`, `bootstrap/app.php`, `config/`, `artisan`, `public/index.php`, `storage/`, `.gitignore` |
| 2 | Kevin | Database: migration, model, seeder | `database/migrations/*`, `app/Models/*`, `database/seeders/*` |
| 3 | Kevin | Registrasi, login, logout + tampilan tamu | `Auth/*Controller`, `views/auth/*`, `views/layouts/guest`, `views/welcome`, `public/css`, `public/js` |
| 4 | Rizal | Transaksi (tambah, edit, hapus, riwayat, filter) + kategori | `TransactionController`, `TransactionRequest`, `CategoryController`, `views/transactions/*`, `views/categories/*` |
| 5 | Zacky | Dashboard + layout aplikasi + laporan | `DashboardController`, `ReportController`, `ReportService`, `views/layouts/app`, `views/dashboard`, `views/reports/*`, `app/Support/Format.php` |
| 6 | Farielyabi | Anggaran bulanan | `BudgetController`, `views/budgets/*` |
| 7 | Faishal | Target tabungan + pengujian + dokumentasi | `SavingsGoalController`, `views/goals/*`, `tests/*`, `README.md`, `lang/id/*` |

Catatan: `routes/web.php` mereferensikan semua controller. Jika ingin aplikasi tetap bisa dijalankan di setiap commit, tambahkan route sedikit demi sedikit seiring commit masing-masing anggota (atau commit `routes/web.php` sebagai bagian dari commit terakhir dan jalankan aplikasi setelah semua selesai).

Perintah dasar tiap anggota:

```bash
git clone <url-repo>
git checkout -b fitur/<nama-fitur>
git add <file-bagian-anda>
git commit -m "feat: <deskripsi singkat>"
git push -u origin fitur/<nama-fitur>
# lalu buat Pull Request ke branch main
```

File yang **tidak** boleh di-commit (sudah ada di `.gitignore`): `.env`, `vendor/`, `database/*.sqlite`, `node_modules/`.
