# Personal Portfolio CRUD — Technical Test Fullstack Intern

Aplikasi landing page profil diri yang seluruh kontennya dikelola lewat sistem CRUD,
dibangun dengan Laravel 11 + Blade + Alpine.js + Tailwind CSS (via Vite) + MySQL.

## Fitur yang Berhasil Diselesaikan
- [x] Data Diri (nama, avatar, headline, bio, kontak) — CRUD (update + upload avatar)
- [x] Pengalaman (Experience) — full CRUD
- [x] Pendidikan (Education) — full CRUD
- [x] Proyek (Project) — full CRUD + upload thumbnail + tag tech stack
- [x] Skill — full CRUD
- [x] Satu halaman dengan toggle Mode View / Mode Edit
- [x] Validasi server-side (Laravel Form Request) di setiap entitas
- [x] Seeder data contoh
- [x] Upload gambar via Laravel Storage

## Instalasi & Menjalankan Secara Lokal

1. Clone repository

2. Install dependency
composer install
npm install

3. Copy environment file
cp .env.example .env
php artisan key:generate

4. Buat database MySQL (lewat phpMyAdmin/HeidiSQL/TablePlus/CLI), lalu sesuaikan di `.env`:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=

5. Jalankan migration & seeder
php artisan migrate --seed

6. Buat symlink storage (untuk upload gambar)
php artisan storage:link

7. Jalankan server (buka 2 terminal)
php artisan serve
npm run dev

8. Buka `http://127.0.0.1:8000`

## Struktur Database
- `profiles` — data diri (1 baris)
- `experiences` — belongsTo profile
- `educations` — belongsTo profile
- `projects` — belongsTo profile
- `skills` — belongsTo profile