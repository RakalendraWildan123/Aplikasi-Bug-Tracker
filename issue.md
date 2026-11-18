# Planning: Implementasi Tabel `projects` (Migration, Enum, Model, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel database untuk menyimpan data Project pada aplikasi **Laravel 12 + MySQL**, lengkap dengan PHP Backed Enum, Migration, Eloquent Model, Factory, Seeder idempoten, dan pengujian otomatis.

Dokumen ini disusun langkah demi langkah agar dapat langsung diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** secara konsisten dengan pola yang sudah ada pada tabel `developers`.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Gunakan nama tabel plural **`projects`** (standar konvensi Laravel Eloquent agar otomatis terhubung dengan model `Project` dan konsisten dengan `users` serta `developers`). Seluruh nama kolom **wajib menggunakan `snake_case` huruf kecil**.  
> *(Jika kebutuhan eksternal/ERD mewajibkan nama tabel singular `project`, ubah nama tabel di migrasi menjadi `project` dan tambahkan `protected $table = 'project';` pada Model `Project`.)*

| Nama Kolom | Tipe SQL | Panjang / Nilai | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik project |
| `name` | `VARCHAR` | `150` | `NOT NULL` | `$table->string('name', 150);` | Nama project |
| `code` | `VARCHAR` | `150` | `UNIQUE`, `NOT NULL` | `$table->string('code', 150)->unique();` | Kode unik project (contoh: `INTRA`, `KEU`, `HRIS`) |
| `description` | `TEXT` | - | `NULLABLE` | `$table->text('description')->nullable();` | Deskripsi singkat/detail project |
| `repo_url` | `VARCHAR` | `255` | `NULLABLE` | `$table->string('repo_url', 255)->nullable();` | URL repository Git (GitHub/GitLab/dll.) |
| `status` | `ENUM` | `'active'`, `'maintenance'`, `'archived'` | `NOT NULL`, `DEFAULT 'active'`, `INDEX` | `$table->enum('status', ['active', 'maintenance', 'archived'])->default('active')->index();` | Status siklus hidup project |
| `color` | `VARCHAR` | `50` | `NOT NULL` | `$table->string('color', 50);` | Warna identitas project (hex mis. `#4F46E5` atau class Tailwind mis. `bg-indigo-600`) |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat PHP Backed Enum untuk `status`
1. Buat file baru `app/Enums/ProjectStatus.php` dengan tipe `enum ProjectStatus: string`.
2. Definisikan 3 case dengan nilai string **huruf kecil persis** sesuai spesifikasi:
   - `Active = 'active'`
   - `Maintenance = 'maintenance'`
   - `Archived = 'archived'`
3. Tambahkan helper static method `values(): array` yang mengembalikan `array_column(self::cases(), 'value')` untuk dipakai pada validasi, factory, dan test.

### Tahap 2: Buat File Migration Tabel `projects`
1. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_projects_table
   ```
2. Pada method `up()`, buat tabel `projects` sesuai spesifikasi tabel di atas:
   - Tentukan panjang `VARCHAR` secara eksplisit (`150`, `150`, `255`, `50`).
   - Berikan constraint `->unique()` pada `code`.
   - **Hardcode array literal** `['active', 'maintenance', 'archived']` di dalam `$table->enum('status', ...)` (jangan mengimpor class Enum di migration agar histori migrasi tetap stabil jika Enum berubah di masa depan).
   - Berikan `->default('active')->index()` pada kolom `status`.
   - Jadikan `description` dan `repo_url` bersifat `->nullable()`.
   - Sertakan `$table->timestamps();`.
3. Pada method `down()`, pastikan berisi `Schema::dropIfExists('projects');`.

### Tahap 3: Buat Eloquent Model `Project`
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model Project
   ```
2. Di `app/Models/Project.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`: `['name', 'code', 'description', 'repo_url', 'status', 'color']`.
   - Definisikan method `casts(): array` (konvensi Laravel 12):
     - `'status' => ProjectStatus::class`
   - Tambahkan query scope agar query umum lebih rapi:
     - `scopeActive(Builder $query): Builder` -> memfilter `status = ProjectStatus::Active`.
     - `scopeStatus(Builder $query, ProjectStatus|string $status): Builder` -> memfilter berdasarkan status tertentu.

### Tahap 4: Buat Factory dan Seeder
1. **Factory (`database/factories/ProjectFactory.php`):**
   - Generate via `php artisan make:factory ProjectFactory --model=Project`.
   - Gunakan helper multibyte-safe (`Illuminate\Support\Str`) bila memanipulasi string.
   - Pastikan data dummy valid dan sesuai batas panjang kolom:
     - `name`: nama project acak (maks 150 karakter).
     - `code`: kode unik huruf kapital (mis. `Str::upper(fake()->unique()->lexify('PRJ-????'))`, maks 150 karakter).
     - `description`: `fake()->paragraph()`.
     - `repo_url`: `'https://github.com/org/' . Str::slug($name)` (maks 255 karakter).
     - `status`: `ProjectStatus::Active`.
     - `color`: pilih acak dari daftar warna (maks 50 karakter, mis. `#4F46E5`, `#10B981`, `#F59E0B`, `#EF4444` atau class Tailwind `bg-indigo-600`, dll.).
   - Tambahkan state helper pada factory: `maintenance()` dan `archived()` untuk mempermudah testing tiap status.
2. **Seeder (`database/seeders/ProjectSeeder.php`):**
   - Generate via `php artisan make:seeder ProjectSeeder`.
   - Isi minimal 3 data contoh realistis sesuai contoh kode di spesifikasi dan mencakup ketiga status (`active`, `maintenance`, `archived`), misalnya:
     - `INTRA` — Portal Intranet Perusahaan (`active`)
     - `KEU` — Sistem Keuangan & Anggaran (`maintenance`)
     - `HRIS` — Human Resource Information System (`archived` atau `active` + tambah 1 project lain agar seluruh status terwakili)
   - Gunakan `Project::updateOrCreate(['code' => ...], [...])` agar seeder bersifat **idempoten** (aman dijalankan berkali-kali).
   - Daftarkan `ProjectSeeder::class` di `DatabaseSeeder.php` (pastikan juga baris lain di `DatabaseSeeder` tidak error saat dijalankan ulang).

### Tahap 5: Jalankan & Verifikasi Migrasi di Database
1. Pastikan service MySQL aktif dan kredensial `.env` valid.
2. Jalankan migrasi dan uji rollback:
   ```bash
   php artisan migrate
   php artisan migrate:rollback --step=1
   php artisan migrate
   ```
3. Jalankan seeder 2 kali untuk memastikan idempotensi:
   ```bash
   php artisan db:seed --class=ProjectSeeder
   php artisan db:seed --class=ProjectSeeder
   ```
4. Verifikasi struktur tabel di MySQL (`DESCRIBE projects;`) untuk memastikan tipe data, panjang kolom, nilai `ENUM`, index `UNIQUE` pada `code`, index pada `status`, dan default `status = 'active'` sudah sesuai.

### Tahap 6: Buat Pengujian Otomatis (Feature Test)
1. Buat file `tests/Feature/ProjectTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `projects` ada dan memiliki semua kolom (`id`, `name`, `code`, `description`, `repo_url`, `status`, `color`, `created_at`, `updated_at`).
   - **Default `status` & Kolom Nullable:** Membuat project tanpa `status`, `description`, dan `repo_url` berhasil dengan `status === ProjectStatus::Active`, `description === null`, dan `repo_url === null`.
   - **Constraint Unique `code`:** Menyimpan 2 project dengan `code` sama melempar `QueryException`.
   - **Validasi Nilai Enum `status`:** Memasukkan nilai status tidak valid (mis. `'invalid_status'`) langsung ke database ditolak (`QueryException`), dan lewat Model melempar `ValueError`.
   - **Casting & Query Scope:** `status` ter-cast menjadi `ProjectStatus`, serta `Project::active()` dan `Project::status(...)` memfilter dengan benar.
   - **Seeder Idempoten:** Menjalankan `ProjectSeeder` dua kali menghasilkan jumlah record konsisten dan mencakup seluruh nilai `ProjectStatus`.
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan pakai huruf kapital pada nama kolom SQL** (`Id`, `Code`, `Repo_url`, `Status`). Wajib `snake_case` huruf kecil.
- **Jangan lupa batas panjang `VARCHAR`** (`150`, `150`, `255`, `50`).
- **Jangan impor class Enum di dalam file migration** — gunakan array literal `['active', 'maintenance', 'archived']`.
- **Pastikan nilai `ENUM` huruf kecil persis:** `'active'`, `'maintenance'`, `'archived'`.
- **Jangan ubah file migrasi lama** yang sudah ada; buat file migrasi baru.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Enum `app/Enums/ProjectStatus.php` dibuat dengan 3 nilai status (`active`, `maintenance`, `archived`).
- [ ] Migration `create_projects_table` dibuat dengan spesifikasi kolom, panjang tipe data, `UNIQUE` pada `code`, `ENUM` literal dengan default `'active'` + index, dan rollback bersih.
- [ ] Model `app/Models/Project.php` memiliki `$fillable`, `casts()`, dan query scope (`active`, `status`).
- [ ] `ProjectFactory` (dengan state `maintenance` & `archived`) dan `ProjectSeeder` (idempoten, memakai contoh kode `INTRA`, `KEU`, `HRIS`) tersedia dan berfungsi.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed --class=ProjectSeeder` berjalan tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk test positif & negatif untuk tabel `projects`).
