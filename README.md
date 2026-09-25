## Fitur

* Melihat daftar kegiatan
* Melihat detail kegiatan
* Menambahkan kegiatan
* Mengubah kegiatan
* Menghapus kegiatan
* Filter kegiatan berdasarkan status
* Validasi input menggunakan Form Request
* Validasi perubahan status menggunakan `ActivityService`
* Route Model Binding

## Struktur Utama

activity-manager/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ActivityController.php
│   │   └── Requests/
│   │       ├── StoreActivityRequest.php
│   │       └── UpdateActivityRequest.php
│   ├── Models/
│   │   └── Activity.php
│   └── Services/
│       └── ActivityService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── ActivitySeeder.php
├── resources/
│   └── views/
│       ├── activities/
│       └── layouts/
├── routes/
│   └── web.php
├── .env.example
├── composer.json
└── README.md

## Persyaratan

Pastikan sudah terpasang:

* PHP 8.3 atau lebih baru
* Composer

Cek versi dengan:

php -v
composer -V
git --version

## Instalasi

### 1. Clone repository

git clone https://github.com/shiningsstars/Proyek-3-Pengembangan-Perangkat-Lunak.git
cd activity-manager

### 2. Install dependency

composer install

### 3. Buat file `.env`

Pada Windows PowerShell:

Copy-Item .env.example .env

### 4. Generate application key

php artisan key:generate

### 5. Jalankan migration dan seeder

php artisan migrate --seed

Perintah tersebut akan membuat tabel database dan memasukkan data awal kegiatan dari `ActivitySeeder`.

### 6. Jalankan program

php artisan serve

Program dapat diakses melalui:

http://127.0.0.1:8000

Halaman utama pengelolaan kegiatan:

http://127.0.0.1:8000/activities

## Route Utama

Aplikasi menggunakan resource route:

Route::resource('activities', ActivityController::class)

Route yang tersedia:

| Method    | URL                           | Fungsi                      |
| --------- | ----------------------------- | --------------------------- |
| GET       | `/activities`                 | Menampilkan daftar kegiatan |
| GET       | `/activities/create`          | Form tambah kegiatan        |
| POST      | `/activities`                 | Menyimpan kegiatan baru     |
| GET       | `/activities/{activity}`      | Menampilkan detail kegiatan |
| GET       | `/activities/{activity}/edit` | Form edit kegiatan          |
| PUT/PATCH | `/activities/{activity}`      | Memperbarui kegiatan        |
| DELETE    | `/activities/{activity}`      | Menghapus kegiatan          |

## Validasi

Input kegiatan divalidasi menggunakan:

* `StoreActivityRequest`
* `UpdateActivityRequest`

Aturan utama:

* `title` wajib diisi, minimal 5 karakter dan maksimal 100 karakter.
* `description` bersifat opsional dengan maksimal 1000 karakter.
* `activity_date` wajib berupa tanggal.
* `category` wajib diisi.
* `status` hanya dapat menggunakan:

  * `Planned`
  * `Ongoing`
  * `Done`

## Aturan Perubahan Status

Perubahan status kegiatan ditangani oleh `ActivityService`.

Transisi yang diperbolehkan:

Planned  → Planned
Planned  → Ongoing

Ongoing  → Ongoing
Ongoing  → Done

Done     → Done

Transisi mundur seperti:

```text
Ongoing → Planned
Done    → Ongoing
Done    → Planned

akan ditolak.

## Filter Status

Daftar kegiatan dapat difilter menggunakan parameter query:

/activities?status=Planned

Status yang tersedia:

Planned
Ongoing
Done

Jika parameter status tidak valid, aplikasi mengabaikannya dan menampilkan seluruh kegiatan.

## Pembagian Tanggung Jawab

Aplikasi menggunakan pemisahan tanggung jawab sebagai berikut:

* **Route** mengatur pemetaan URL ke controller.
* **Controller** mengatur request dan response.
* **Form Request** menangani validasi input.
* **ActivityService** menangani business rule perubahan status.
* **Model/Eloquent** menangani interaksi dengan database.
* **Blade** digunakan untuk menampilkan data kepada pengguna.

## Status Project

Project telah menerapkan:

* CRUD Activity
* Form Request validation
* Route Model Binding
* Filter status
* Business rule pada `ActivityService`
* Laravel Pint
* Acceptance Criteria Modul 3
* Request lifecycle dari route hingga Blade
