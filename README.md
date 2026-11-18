# Aplikasi Bug Tracker — Laravel 12 Foundation

Fondasi project **Laravel 12** untuk Aplikasi Monitoring & Manajemen IT Developer / Bug Tracker.

## Tech Stack
- **Backend:** Laravel 12 (PHP >= 8.2)
- **Frontend:** Vue 3 Standalone (`vue/dist/vue.esm-bundler.js`), Tailwind CSS v4, HTML5 Drag & Drop API (native)
- **Database:** MySQL
- **Cache Ekspor:** Redis (`predis/predis`, store khusus `export`)
- **Engine Ekspor:** Laravel Snappy (`barryvdh/laravel-snappy` + `wkhtmltopdf` / `wkhtmltoimage`)

## Prasyarat Environment
1. PHP >= 8.2 dengan ekstensi `pdo_mysql` dan Composer 2.x
2. Node.js >= 20 & npm
3. MySQL Server 8.x
4. Redis Server (untuk caching ekspor)
5. Binary `wkhtmltopdf` (dan `wkhtmltoimage` bila diperlukan):
   - **Windows:** Install `wkhtmltopdf` lalu set `WKHTML_PDF_BINARY="\"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe\""` di `.env`
   - **Linux/macOS:** Install `wkhtmltopdf` lalu set `WKHTML_PDF_BINARY=/usr/local/bin/wkhtmltopdf` di `.env`

## Langkah Setup
```bash
cp .env.example .env
composer install
php artisan key:generate
npm install
npm run build
```

Sesuaikan kredensial MySQL, Redis, dan path binary Snappy pada `.env`, lalu jalankan migrasi:
```bash
php artisan migrate
```

## Endpoint & Fitur Smoke Test
- `/` — Halaman uji **Vue 3 Standalone + Tailwind CSS + HTML5 Drag & Drop API** native.
- `/exports/sample-pdf` — Endpoint uji ekspor PDF dengan **Snappy** + caching **Redis** (cek header `X-Export-Cache: MISS` lalu `HIT`).
- `/exports/sample-pdf?refresh=1` — Paksa regenerasi cache ekspor PDF.

## Pengujian Otomatis
```bash
php artisan test
```
