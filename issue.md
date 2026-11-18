# Planning: Implementasi Tabel `modules` (Migration, Model, Relasi, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel database untuk menyimpan data **Modul** yang terhubung ke tabel **`projects`** (relasi *One-to-Many*: 1 Project memiliki banyak Module) pada aplikasi **Laravel 12 + MySQL**, lengkap dengan Migration, Foreign Key Constraint, Eloquent Model + Relasi dua arah, Factory, Seeder idempoten, dan pengujian otomatis.

Dokumen ini disusun langkah demi langkah agar mudah diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** dengan hasil yang rapi dan aman.

> **Prasyarat Wajib:** Pastikan tabel `projects` beserta Model `App\Models\Project` dan `ProjectSeeder` (Issue #5 / PR #6) sudah tersedia di branch kerja sebelum menjalankan migrasi ini, karena kolom `project_id` mereferensikan `projects.id`.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Gunakan nama tabel plural Bahasa Inggris **`modules`** (Model: `Module`) agar konsisten dengan tabel `users`, `developers`, dan `projects` serta otomatis mengikuti konvensi Laravel Eloquent. Seluruh nama kolom **wajib `snake_case` huruf kecil**.  
> *(Jika kebutuhan ERD mewajibkan nama literal `modul`, ubah nama tabel di migrasi menjadi `modul` dan tambahkan `protected $table = 'modul';` pada Model.)*

| Nama Kolom | Tipe SQL | Panjang / Referensi | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik modul |
| `project_id` | `BIGINT UNSIGNED` | FK -> `projects.id` | `NOT NULL`, `FOREIGN KEY`, `ON DELETE CASCADE`, `INDEX` | `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();` | Relasi ke tabel `projects` |
| `name` | `VARCHAR` | `150` | `NOT NULL` | `$table->string('name', 150);` | Nama modul (mis. `Autentikasi & SSO`, `Laporan Keuangan`) |
| `description` | `TEXT` | - | `NULLABLE` | `$table->text('description')->nullable();` | Deskripsi fungsi/cakupan modul |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat File Migration Tabel `modules`
1. Pastikan file migrasi `create_projects_table` sudah ada dan timestamp file migrasi baru ini **lebih baru** dari `create_projects_table` (agar urutan migrasi tidak error Foreign Key).
2. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_modules_table
   ```
3. Pada method `up()`, buat tabel `modules` sesuai tabel spesifikasi di atas:
   - Gunakan `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();` agar tipe kolom otomatis `BIGINT UNSIGNED` (cocok persis dengan `projects.id`), ter-index, dan menghapus modul otomatis saat project dihapus.
   - Tentukan panjang `VARCHAR` secara eksplisit: `$table->string('name', 150);`.
   - Jadikan `description` nullable: `$table->text('description')->nullable();`.
   - Sertakan `$table->timestamps();`.
4. Pada method `down()`, pastikan berisi `Schema::dropIfExists('modules');`.

### Tahap 2: Buat Eloquent Model `Module` & Lengkapi Relasi di `Project`
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model Module
   ```
2. Di `app/Models/Module.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`: `['project_id', 'name', 'description']`.
   - Tambahkan relasi `BelongsTo` ke `Project`:
     ```php
     public function project(): BelongsTo
     {
         return $this->belongsTo(Project::class);
     }
     ```
3. Di `app/Models/Project.php`:
   - Tambahkan relasi kebalikannya (`HasMany`) ke `Module`:
     ```php
     public function modules(): HasMany
     {
         return $this->hasMany(Module::class);
     }
     ```

### Tahap 3: Buat Factory (`ModuleFactory`) dan Seeder (`ModuleSeeder`)
1. **Factory (`database/factories/ModuleFactory.php`):**
   - Generate via `php artisan make:factory ModuleFactory --model=Module`.
   - Gunakan helper multibyte-safe (`Illuminate\Support\Str`).
   - Definisikan atribut default:
     - `'project_id' => Project::factory()` (otomatis membuat project jika tidak ditentukan lewat `->for($project)`).
     - `'name'`: nama modul acak maksimal 150 karakter.
     - `'description'`: `fake()->paragraph()`.
2. **Seeder (`database/seeders/ModuleSeeder.php`):**
   - Generate via `php artisan make:seeder ModuleSeeder`.
   - Pastikan data project sudah tersedia (jika project dengan kode `INTRA`, `KEU`, `HRIS` belum ada, panggil `$this->call(ProjectSeeder::class);` atau cari berdasarkan `code`).
   - Buat minimal 2 modul contoh per project (`INTRA`, `KEU`, `HRIS`), misalnya:
     - `INTRA`: `Autentikasi & SSO`, `Portal Berita & Pengumuman`
     - `KEU`: `Pengelolaan Anggaran`, `Laporan Keuangan`
     - `HRIS`: `Manajemen Absensi`, `Penggajian & Slip Gaji`
   - Gunakan `Module::updateOrCreate(['project_id' => $project->id, 'name' => $item['name']], $item)` agar seeder bersifat **idempoten** (aman dijalankan berkali-kali tanpa duplikasi).
   - Daftarkan `ModuleSeeder::class` pada `DatabaseSeeder.php` **setelah** `ProjectSeeder::class`.

### Tahap 4: Jalankan & Verifikasi Migrasi di Database
1. Pastikan service MySQL aktif dan kredensial `.env` valid.
2. Jalankan migrasi dan uji rollback:
   ```bash
   php artisan migrate
   php artisan migrate:rollback --step=1
   php artisan migrate
   ```
3. Jalankan seeder 2 kali untuk memastikan idempotensi:
   ```bash
   php artisan db:seed --class=ModuleSeeder
   php artisan db:seed --class=ModuleSeeder
   ```
4. Verifikasi struktur & relasi tabel di MySQL (`SHOW CREATE TABLE modules;`) untuk memastikan tipe `BIGINT UNSIGNED`, `VARCHAR(150)`, `TEXT`, serta constraint `FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE` sudah terbentuk.

### Tahap 5: Buat Pengujian Otomatis (Feature Test)
1. Buat file `tests/Feature/ModuleTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `modules` ada dan memiliki kolom `id`, `project_id`, `name`, `description`, `created_at`, `updated_at`.
   - **Kolom Nullable:** Membuat `Module` tanpa `description` berhasil dan menyimpan `null`.
   - **Foreign Key Constraint:** Mencoba insert `Module` dengan `project_id` yang tidak ada di tabel `projects` (mis. `999999`) melempar `QueryException`.
   - **Cascade Delete:** Menghapus `Project` otomatis menghapus seluruh `Module` yang berelasi dengannya.
   - **Relasi Eloquent Dua Arah:** `$module->project` mengembalikan instance `Project` yang tepat, dan `$project->modules` mengembalikan seluruh modul milik project tersebut.
   - **Seeder Idempoten:** Menjalankan `ModuleSeeder` dua kali menghasilkan jumlah record konsisten tanpa duplikasi.
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus tanpa error.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan pakai huruf kapital pada nama kolom SQL** (`Id`, `Project_id`, `Name`, `Description`). Wajib `snake_case` huruf kecil.
- **Jangan pakai tipe `integer` atau `bigInteger` bertanda (signed)** untuk `project_id` karena akan gagal membuat Foreign Key ke `projects.id` (`BIGINT UNSIGNED`) di MySQL. Selalu gunakan `$table->foreignId('project_id')`.
- **Jangan lupa batas panjang `VARCHAR(150)`** pada kolom `name`.
- **Jangan lupa menambahkan relasi `modules()` di `Project.php`** selain `project()` di `Module.php`.
- **Jangan jalankan `ModuleSeeder` sebelum `ProjectSeeder`** di `DatabaseSeeder.php`.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Migration `create_modules_table` dibuat dengan `id`, `project_id` (`FOREIGN KEY -> projects.id` `ON DELETE CASCADE`), `name` (`VARCHAR(150)`), `description` (`TEXT`, nullable), dan `timestamps()`.
- [ ] Model `app/Models/Module.php` (`$fillable` + relasi `project()`) dan relasi `modules()` di `app/Models/Project.php` sudah lengkap.
- [ ] `ModuleFactory` dan `ModuleSeeder` (idempoten) tersedia serta terdaftar setelah `ProjectSeeder` di `DatabaseSeeder.php`.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed --class=ModuleSeeder` berjalan tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk uji Foreign Key, cascade delete, relasi dua arah, dan seeder idempoten).
