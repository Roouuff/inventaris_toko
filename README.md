# Inventaris Toko Komputer

Aplikasi web manajemen data **kategori** dan **barang** berbasis **Laravel 13**, dilengkapi sistem login (authentication), CRUD, validasi, serta relasi database antara kategori dan barang.

> Dibuat sebagai tugas Laravel menggunakan **VS Code** dan **Laragon**.

---

## Daftar Isi

1. [Fitur](#fitur)
2. [Teknologi](#teknologi)
3. [Struktur Database](#struktur-database)
4. [Prasyarat](#prasyarat)
5. [Instalasi](#instalasi)
6. [Menjalankan Migration dan Seeder](#menjalankan-migration-dan-seeder)
7. [Menjalankan Aplikasi](#menjalankan-aplikasi)
8. [Akun Pengujian](#akun-pengujian)
9. [Data Seeder](#data-seeder)
10. [Daftar Route](#daftar-route)
11. [Aturan Validasi dan Integritas Data](#aturan-validasi-dan-integritas-data)
12. [Struktur File Penting](#struktur-file-penting)
13. [Panduan Pengujian](#panduan-pengujian)
14. [Ketentuan Repository GitHub](#ketentuan-repository-github)
15. [Mengunggah ke GitHub](#mengunggah-ke-github)
16. [Troubleshooting](#troubleshooting)

---

## Fitur

**Authentication**
- Halaman login dengan validasi input.
- Logout yang menghapus sesi dan mengganti token CSRF.
- Semua halaman aplikasi dilindungi middleware `auth`. Pengguna yang belum login otomatis diarahkan ke halaman login.
- Password pengguna disimpan dalam bentuk hash (bcrypt).

**CRUD Kategori**
- Menambahkan, menampilkan, mengubah, dan menghapus kategori.
- Menampilkan jumlah barang pada setiap kategori.
- Kategori yang masih memiliki barang **tidak dapat dihapus** dan akan menampilkan pesan penolakan.

**CRUD Barang**
- Menambahkan, menampilkan, mengubah, dan menghapus barang.
- Daftar barang menampilkan nama kategori yang diambil melalui relasi Eloquent.
- Kategori dipilih dari dropdown berisi data kategori yang tersedia di database.

**Database**
- Migration untuk tabel `categories` dan `items` dengan foreign key `category_id`.
- Seeder untuk 1 pengguna, 3 kategori, dan 5 barang.

---

## Teknologi

| Komponen | Keterangan |
|---|---|
| Framework | Laravel 13 |
| Bahasa | PHP 8.3 atau lebih baru (dikembangkan dengan PHP 8.4) |
| Database | MySQL |
| Tampilan | Blade Template + Bootstrap 5 (via CDN) |
| Environment | Laragon (Windows) |
| Editor | Visual Studio Code |
| Dependency manager | Composer |

---

## Struktur Database

Database yang digunakan bernama `inventaris_db`.

### Tabel `categories`

| Field | Tipe Data | Keterangan |
|---|---|---|
| id | BIGINT | Primary key |
| name | VARCHAR | Nama kategori |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu pembaruan |

### Tabel `items`

| Field | Tipe Data | Keterangan |
|---|---|---|
| id | BIGINT | Primary key |
| category_id | BIGINT | Foreign key ke `categories.id` |
| name | VARCHAR | Nama barang |
| price | DECIMAL(12,2) | Harga barang |
| stock | INTEGER (unsigned) | Jumlah stok |
| created_at | TIMESTAMP | Waktu pembuatan |
| updated_at | TIMESTAMP | Waktu pembaruan |

### Relasi

```
categories
    id (PK)
    name
       |
       | 1
       |
       | N
       v
items
    id (PK)
    category_id (FK)
    name
    price
    stock
```

- Satu kategori dapat memiliki banyak barang.
- Setiap barang harus memiliki tepat satu kategori.
- Foreign key `category_id` memakai `restrictOnDelete()`, sehingga database menolak penghapusan kategori yang masih dipakai barang.
- Relasi diimplementasikan dengan Eloquent:
  - `Category` memakai `hasMany()` ke `Item`
  - `Item` memakai `belongsTo()` ke `Category`

---

## Prasyarat

Pastikan perangkat sudah memiliki:

- **PHP 8.3 atau lebih baru** (disertakan di Laragon)
- **Composer** (disertakan di Laragon)
- **MySQL** (disertakan di Laragon)
- **Git**
- phpMyAdmin atau HeidiSQL untuk mengelola database (opsional)

Cek versi dengan perintah berikut:

```bash
php -v
composer -V
git --version
```

---

## Instalasi

Langkah berikut menggunakan **Laragon** di Windows. Buka **Terminal** dari Laragon.

### 1. Nyalakan layanan Laragon

Klik **Start All** sampai Apache/Nginx dan MySQL berjalan.

### 2. Clone repository

```bash
cd C:\laragon\www
git clone https://github.com/USERNAME/inventaris.git
cd inventaris
```

> Ganti `USERNAME` dengan nama pengguna GitHub pemilik repository.

### 3. Install dependensi

```bash
composer install
```

Perintah ini membuat folder `vendor/` berdasarkan `composer.json` dan `composer.lock`.

### 4. Salin file environment

```bash
copy .env.example .env
```

Di Linux atau macOS gunakan `cp .env.example .env`.

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Buat database

Pilih salah satu cara berikut.

**Lewat phpMyAdmin:** buka phpMyAdmin, klik **New**, isi nama database `inventaris_db` dengan collation `utf8mb4_unicode_ci`, lalu klik **Create**.

**Lewat terminal:**

```bash
mysql -u root -e "CREATE DATABASE inventaris_db"
```

### 7. Atur koneksi database

Buka file `.env`, lalu sesuaikan bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_db
DB_USERNAME=root
DB_PASSWORD=
```

> Pada Laragon, user `root` secara bawaan tidak memiliki password. Sesuaikan jika konfigurasi MySQL Anda berbeda.
> Laravel versi baru memakai SQLite secara bawaan, jadi nilai `DB_CONNECTION` wajib diubah ke `mysql`. Hapus tanda `#` di depan baris yang masih berkomentar.

Jika `.env` diubah setelah aplikasi pernah dijalankan, bersihkan cache konfigurasi:

```bash
php artisan config:clear
```

---

## Menjalankan Migration dan Seeder

Jalankan migration untuk membuat tabel:

```bash
php artisan migrate
```

Lalu jalankan seeder untuk mengisi data awal (pengguna, kategori, dan barang):

```bash
php artisan db:seed
```

Cek status migration:

```bash
php artisan migrate:status
```

Semua migration harus berstatus `Ran`.

### Membangun ulang database dari awal

Untuk membangun ulang database pengujian dari awal, jalankan:

```bash
php artisan migrate:fresh --seed
```

> ⚠️ **Peringatan:** `migrate:fresh` **menghapus seluruh tabel** yang ada, lalu membuatnya kembali dan menjalankan seeder. Perintah ini hanya boleh digunakan pada **database pengembangan atau pengujian**. Jangan pernah menjalankannya pada database yang berisi data penting.

> Menjalankan `php artisan db:seed` lebih dari sekali pada database yang sama akan menimbulkan error, karena email pengguna harus unik. Gunakan `migrate:fresh --seed` jika ingin mengulang dari awal.

---

## Menjalankan Aplikasi

### Cara 1: Artisan Serve

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser.

### Cara 2: Virtual host otomatis Laragon

Karena project berada di `C:\laragon\www\inventaris`, Laragon biasanya membuat alamat otomatis:

```
http://inventaris.test
```

Jika alamat tersebut belum bisa dibuka, klik kanan ikon Laragon, pilih **Apache** (atau **Nginx**) > **Reload**, atau **Stop All** lalu **Start All**.

Setelah aplikasi terbuka, pengguna yang belum login akan diarahkan ke halaman login.

---

## Akun Pengujian

Akun berikut dibuat oleh seeder untuk keperluan pengembangan dan pengujian:

| Email | Password |
|---|---|
| `admin@toko.test` | `password123` |

> Akun ini hanya untuk pengujian lokal. Jangan dipakai di lingkungan produksi.

---

## Data Seeder

### Kategori

| ID | Nama Kategori |
|---|---|
| 1 | Aksesori |
| 2 | Perangkat Komputer |
| 3 | Penyimpanan |

### Barang

| Nama Barang | Kategori | Harga | Stok |
|---|---|---:|---:|
| Keyboard | Aksesori | 150.000 | 10 |
| Mouse | Aksesori | 75.000 | 20 |
| Monitor | Perangkat Komputer | 1.500.000 | 5 |
| Headset | Aksesori | 250.000 | 8 |
| Flashdisk | Penyimpanan | 85.000 | 15 |

Barang dibuat melalui relasi (`$kategori->items()->create(...)`), sehingga setiap barang otomatis terhubung ke kategori yang valid.

---

## Daftar Route

| Method | URL | Keterangan | Akses |
|---|---|---|---|
| GET | `/login` | Halaman login | Guest |
| POST | `/login` | Proses login | Guest |
| POST | `/logout` | Proses logout | Login |
| GET | `/` | Diarahkan ke daftar barang | Login |
| GET | `/categories` | Daftar kategori | Login |
| GET | `/categories/create` | Form tambah kategori | Login |
| POST | `/categories` | Simpan kategori baru | Login |
| GET | `/categories/{id}/edit` | Form ubah kategori | Login |
| PUT/PATCH | `/categories/{id}` | Perbarui kategori | Login |
| DELETE | `/categories/{id}` | Hapus kategori | Login |
| GET | `/items` | Daftar barang beserta kategori | Login |
| GET | `/items/create` | Form tambah barang | Login |
| POST | `/items` | Simpan barang baru | Login |
| GET | `/items/{id}/edit` | Form ubah barang | Login |
| PUT/PATCH | `/items/{id}` | Perbarui barang | Login |
| DELETE | `/items/{id}` | Hapus barang | Login |

Untuk melihat daftar lengkap di terminal:

```bash
php artisan route:list --except-vendor
```

---

## Aturan Validasi dan Integritas Data

### Kategori

| Field | Aturan |
|---|---|
| name | Wajib diisi, berupa teks, maksimal 255 karakter |

### Barang

| Field | Aturan |
|---|---|
| category_id | Wajib diisi dan harus ada di tabel `categories` |
| name | Wajib diisi, berupa teks, maksimal 255 karakter |
| price | Wajib diisi, berupa angka, lebih besar dari 0 |
| stock | Wajib diisi, bilangan bulat, minimal 0 |

### Login

| Field | Aturan |
|---|---|
| email | Wajib diisi dan berformat email |
| password | Wajib diisi |

### Integritas relasi

- Kategori yang masih memiliki barang tidak dapat dihapus. Aplikasi menampilkan pesan penolakan beserta jumlah barangnya.
- Foreign key di level database (`restrictOnDelete`) menjadi pengaman kedua jika pengecekan di aplikasi terlewati.
- Barang dengan `category_id` yang tidak valid ditolak oleh validasi aplikasi dan oleh database.

---

## Struktur File Penting

```
inventaris/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php        # Login dan logout
│   │   ├── CategoryController.php    # CRUD kategori
│   │   └── ItemController.php        # CRUD barang
│   └── Models/
│       ├── Category.php              # hasMany(Item)
│       ├── Item.php                  # belongsTo(Category)
│       └── User.php
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_categories_table.php
│   │   └── xxxx_create_items_table.php
│   └── seeders/
│       └── DatabaseSeeder.php        # Pengguna, kategori, dan barang
├── resources/views/
│   ├── layouts/app.blade.php         # Layout utama + navbar
│   ├── auth/login.blade.php          # Halaman login
│   ├── categories/                   # index, create, edit, _form
│   └── items/                        # index, create, edit, _form
├── routes/web.php                    # Seluruh route aplikasi
├── .env.example                      # Contoh konfigurasi (tanpa kredensial)
├── composer.json                     # Konfigurasi dependensi
└── composer.lock
```

---

## Panduan Pengujian

Gunakan akun pada bagian [Akun Pengujian](#akun-pengujian), lalu cek satu per satu:

| No | Pengujian | Hasil yang diharapkan |
|---|---|---|
| 1 | Login dan logout | Login benar masuk ke daftar barang. Login salah menampilkan pesan error. Logout kembali ke `/login`. |
| 2 | Akses halaman tanpa login | Membuka `/items` atau `/categories` tanpa login diarahkan ke `/login`. Setelah logout, tombol Back lalu refresh tetap diarahkan ke `/login`. |
| 3 | CRUD kategori | Tambah, ubah, dan hapus kategori berhasil. |
| 4 | CRUD barang | Tambah, ubah, dan hapus barang berhasil. |
| 5 | Pemilihan kategori | Dropdown kategori pada form tambah dan ubah barang berisi semua kategori di database. |
| 6 | Nama kategori di daftar barang | Kolom Kategori pada `/items` terisi melalui relasi. |
| 7 | Validasi input | Nama kosong, harga `0` atau `abc`, stok `-1` atau `2.5`, dan kategori tidak valid ditolak dengan pesan yang jelas. |
| 8 | Hapus kategori yang dipakai barang | Ditolak dengan pesan yang menyebut jumlah barang. |
| 9 | Migration dan foreign key | Semua migration berstatus `Ran`. Menyimpan barang dengan `category_id` yang tidak ada ditolak database. |
| 10 | Seeder dan relasi data | Setelah `migrate:fresh --seed`, aplikasi menampilkan 3 kategori dan 5 barang beserta kategorinya. |

### Pembuktian relasi lewat Tinker

```bash
php artisan tinker
```

```php
App\Models\Category::find(1)->items->pluck('name');   // Keyboard, Mouse, Headset
App\Models\Item::first()->category->name;             // Aksesori
App\Models\User::first()->password;                   // Hash berawalan $2y$
```

### Pembuktian foreign key

```php
App\Models\Item::create(['category_id' => 999, 'name' => 'Tes', 'price' => 1000, 'stock' => 1]);
```

Perintah di atas harus menghasilkan error foreign key constraint.

---

## Ketentuan Repository GitHub

Source code wajib diunggah ke GitHub. Repository berisi:

| Ketentuan | Lokasi di repository |
|---|---|
| Project Laravel | Seluruh folder project |
| Model `Category` dan `Item` | `app/Models/` |
| Controller authentication dan CRUD | `app/Http/Controllers/` |
| Migration tabel kategori dan barang | `database/migrations/` |
| Seeder pengguna, kategori, dan barang | `database/seeders/DatabaseSeeder.php` |
| Route dan view | `routes/web.php` dan `resources/views/` |
| File konfigurasi dependensi | `composer.json` dan `composer.lock` |
| README berisi cara instalasi dan menjalankan aplikasi | `README.md` |

**Yang tidak boleh diunggah:**

- File `.env` atau kredensial rahasia lainnya (password database, API key, dan sejenisnya).
- Folder `vendor/` dan `node_modules/` (dibuat ulang dengan `composer install`).

Laravel sudah mengabaikan file-file tersebut lewat `.gitignore`. Gunakan `.env.example` sebagai contoh konfigurasi tanpa data rahasia.

Periksa sebelum push:

```bash
git check-ignore -v .env
git status
```

Perintah pertama harus menampilkan aturan `.gitignore` yang mengabaikan `.env`. Pada hasil `git status`, file `.env` tidak boleh muncul sebagai file yang akan di-commit.

---

## Mengunggah ke GitHub

1. Buat repository kosong di GitHub, misalnya bernama `inventaris`. Jangan centang pilihan untuk menambah README atau `.gitignore` agar tidak bentrok.
2. Di folder project, jalankan:

```bash
git init
git add .
git status
git commit -m "Aplikasi inventaris Laravel: auth, CRUD kategori dan barang"
git branch -M main
git remote add origin https://github.com/USERNAME/inventaris.git
git push -u origin main
```

3. Buka halaman repository di browser, lalu pastikan file `.env` **tidak** ada di daftar file.

Jika `.env` sempat ikut ter-commit, jangan hanya menghapusnya dari commit berikutnya karena isinya tetap tersimpan di riwayat. Ganti password database yang bocor, lalu hapus dari riwayat git sebelum repository dibagikan.

---

## Troubleshooting

| Masalah | Penyebab dan solusi |
|---|---|
| `could not find driver` | Ekstensi `pdo_mysql` belum aktif. Aktifkan di Laragon: Menu > PHP > Extensions > centang `pdo_mysql`. |
| `SQLSTATE[HY000] [1049] Unknown database` | Database `inventaris_db` belum dibuat. Buat dulu di phpMyAdmin. |
| `SQLSTATE[HY000] [1045] Access denied` | `DB_USERNAME` atau `DB_PASSWORD` di `.env` tidak sesuai dengan MySQL Anda. |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL belum berjalan. Klik **Start All** di Laragon. |
| Perubahan `.env` tidak terbaca | Jalankan `php artisan config:clear`. |
| `Failed to open stream: vendor/autoload.php` | Dependensi belum terpasang. Jalankan `composer install`. |
| `No application encryption key has been specified` | Jalankan `php artisan key:generate`. |
| `419 Page Expired` saat submit form | Sesi atau token CSRF kedaluwarsa. Refresh halaman lalu coba lagi. Pastikan form memiliki `@csrf`. |
| `Duplicate entry ... for key 'users_email_unique'` | Seeder dijalankan dua kali. Gunakan `php artisan migrate:fresh --seed`. |
| `inventaris.test` tidak bisa dibuka | Reload Apache/Nginx di Laragon, atau gunakan `php artisan serve`. |

---

## Lisensi

Project ini dibuat untuk keperluan pembelajaran.
