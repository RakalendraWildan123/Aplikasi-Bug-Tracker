# Planning: Implementasi Tabel `bugs` (Migration, Enum, Model, Relasi, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel inti **`bugs`** untuk mencatat, melacak, dan memantau laporan bug/insiden yang terhubung ke tabel **`projects`**, **`modules`**, dan **`developers`** pada aplikasi **Laravel 12 + MySQL**, lengkap dengan 2 PHP Backed Enum (`BugSeverity` & `BugStatus`), Migration + 4 Foreign Key + Index, Eloquent Model + Relasi dua arah, Factory, Seeder idempoten, dan pengujian otomatis.

Dokumen ini disusun langkah demi langkah mengikuti pola tabel `tasks` agar dapat langsung diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** secara konsisten dan minim error.

> **Prasyarat Wajib:** Tabel & Model `developers`, `projects`, dan `modules` (sudah tersedia di branch `main`) menjadi referensi Foreign Key untuk tabel `bugs`.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Gunakan nama tabel plural Bahasa Inggris **`bugs`** (Model: `Bug`) agar konsisten dengan `users`, `developers`, `projects`, `modules`, dan `tasks`. Seluruh nama kolom **wajib `snake_case` huruf kecil**.  
> *(Jika kebutuhan ERD mewajibkan nama singular `bug`, ubah nama tabel di migrasi menjadi `bug` dan tambahkan `protected $table = 'bug';` pada Model `Bug`.)*

| Nama Kolom | Tipe SQL | Panjang / Nilai / Referensi | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik bug |
| `project_id` | `BIGINT UNSIGNED` | FK -> `projects.id` | `NOT NULL`, `ON DELETE CASCADE` | `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();` | Project tempat bug ditemukan |
| `module_id` | `BIGINT UNSIGNED` | FK -> `modules.id` | `NULLABLE`, `ON DELETE SET NULL` | `$table->foreignId('module_id')->nullable()->constrained('modules')->nullOnDelete();` | Modul terkait (opsional / aman jika modul dihapus) |
| `created_by` | `BIGINT UNSIGNED` | FK -> `developers.id` | `NOT NULL`, `ON DELETE RESTRICT` | `$table->foreignId('created_by')->constrained('developers')->restrictOnDelete();` | Developer pencatat bug (wajib eksplisit `'developers'`) |
| `assigned_to` | `BIGINT UNSIGNED` | FK -> `developers.id` | `NULLABLE`, `ON DELETE SET NULL` | `$table->foreignId('assigned_to')->nullable()->constrained('developers')->nullOnDelete();` | Developer penanggung jawab (boleh kosong saat `open`) |
| `title` | `VARCHAR` | `255` | `NOT NULL` | `$table->string('title', 255);` | Ringkasan/judul bug |
| `description` | `TEXT` | - | `NULLABLE` | `$table->text('description')->nullable();` | Detail reproduksi & gejala bug |
| `reported_by` | `VARCHAR` | `150` | `NULLABLE` | `$table->string('reported_by', 150)->nullable();` | Nama pelapor (QA/user/klien, mis. `QA - Sari`) |
| `reported_date` | `DATE` | - | `NULLABLE` | `$table->date('reported_date')->nullable();` | Tanggal bug dilaporkan |
| `severity` | `ENUM` | `'critical'`, `'high'`, `'medium'`, `'low'` | `NOT NULL`, `DEFAULT 'medium'` | `$table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('medium');` | Tingkat keparahan bug |
| `status` | `ENUM` | `'open'`, `'in_progress'`, `'resolved'` | `NOT NULL`, `DEFAULT 'open'` | `$table->enum('status', ['open', 'in_progress', 'resolved'])->default('open');` | Status penanganan bug |
| `root_cause` | `TEXT` | - | `NULLABLE` | `$table->text('root_cause')->nullable();` | Hasil analisis akar masalah |
| `solution_notes` | `TEXT` | - | `NULLABLE` | `$table->text('solution_notes')->nullable();` | Catatan perbaikan yang diterapkan |
| `resolved_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamp('resolved_at')->nullable();` | Waktu aktual bug terselesaikan (`resolved`) |
| `handover_logs` | `JSON` | Array of objects | `NULLABLE` | `$table->json('handover_logs')->nullable();` | Riwayat serah terima penanganan bug antar developer |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

**Index Tambahan (untuk performa Board & Monitoring Bug):**
- `$table->index(['project_id', 'status']);` — filter daftar bug per project berdasarkan status.
- `$table->index(['severity', 'status']);` — filter cepat bug `critical`/`high` yang masih `open`/`in_progress`.
- `$table->index(['assigned_to', 'status']);` — monitoring beban bug aktif per developer.

**Konvensi Struktur JSON untuk `handover_logs` (konsisten dengan tabel `tasks`):**
```json
[
  {
    "from_developer_id": 1,
    "to_developer_id": 2,
    "note": "Reproduksi bug terkonfirmasi pada query agregasi, lanjut perbaikan di backend.",
    "handed_over_at": "2026-11-18T11:15:00Z"
  }
]
```

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat 2 PHP Backed Enum (`BugSeverity` & `BugStatus`)
1. Buat `app/Enums/BugSeverity.php` (`enum BugSeverity: string`) dengan 4 case persis sesuai spesifikasi:
   - `Critical = 'critical'`
   - `High = 'high'`
   - `Medium = 'medium'`
   - `Low = 'low'`
   - Tambahkan static method `values(): array`.
2. Buat `app/Enums/BugStatus.php` (`enum BugStatus: string`) dengan 3 case persis sesuai spesifikasi:
   - `Open = 'open'`
   - `InProgress = 'in_progress'`
   - `Resolved = 'resolved'`
   - Tambahkan static method `values(): array`.

### Tahap 2: Buat File Migration Tabel `bugs`
1. Pastikan file migrasi `create_developers_table`, `create_projects_table`, dan `create_modules_table` sudah ada dan timestamp migrasi baru ini **lebih baru** dari ketiganya.
2. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_bugs_table
   ```
3. Pada method `up()`, definisikan seluruh kolom pada tabel spesifikasi di atas:
   - **Wajib** menuliskan `'developers'` secara eksplisit pada `->constrained('developers')` untuk `created_by` dan `assigned_to`.
   - **Hardcode array literal** pada kedua kolom `$table->enum(...)` (jangan impor class Enum di file migration).
   - Jadikan kolom opsional (`module_id`, `assigned_to`, `description`, `reported_by`, `reported_date`, `root_cause`, `solution_notes`, `resolved_at`, `handover_logs`) bersifat `->nullable()`.
   - Tambahkan 3 composite index: `['project_id', 'status']`, `['severity', 'status']`, dan `['assigned_to', 'status']`.
   - Sertakan `$table->timestamps();`.
4. Pada method `down()`, pastikan berisi `Schema::dropIfExists('bugs');`.

### Tahap 3: Buat Eloquent Model `Bug` & Lengkapi Relasi di Model Lain
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model Bug
   ```
2. Di `app/Models/Bug.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`:
     `['project_id', 'module_id', 'created_by', 'assigned_to', 'title', 'description', 'reported_by', 'reported_date', 'severity', 'status', 'root_cause', 'solution_notes', 'resolved_at', 'handover_logs']`.
   - Definisikan method `casts(): array` (konvensi Laravel 12):
     - `'severity' => BugSeverity::class`
     - `'status' => BugStatus::class`
     - `'reported_date' => 'date'`
     - `'resolved_at' => 'datetime'`
     - `'handover_logs' => 'array'`
   - Definisikan 4 relasi `BelongsTo`:
     - `project(): BelongsTo` -> `$this->belongsTo(Project::class)`
     - `module(): BelongsTo` -> `$this->belongsTo(Module::class)`
     - `creator(): BelongsTo` -> `$this->belongsTo(Developer::class, 'created_by')`
     - `assignee(): BelongsTo` -> `$this->belongsTo(Developer::class, 'assigned_to')`
   - Tambahkan query scope umum (*fail-fast* dengan `::from(...)` bila menerima string):
     - `scopeStatus(Builder $query, BugStatus|string $status): Builder`
     - `scopeSeverity(Builder $query, BugSeverity|string $severity): Builder`
     - `scopeOpen(Builder $query): Builder`
     - `scopeForProject(Builder $query, int $projectId): Builder`
     - `scopeAssignedTo(Builder $query, int $developerId): Builder`
3. Lengkapi relasi kebalikan (`HasMany`) pada Model terkait:
   - `app/Models/Project.php` -> `bugs(): HasMany`
   - `app/Models/Module.php` -> `bugs(): HasMany`
   - `app/Models/Developer.php` -> `createdBugs(): HasMany` (`'created_by'`) dan `assignedBugs(): HasMany` (`'assigned_to'`)

### Tahap 4: Buat Factory (`BugFactory`) dan Seeder (`BugSeeder`)
1. **Factory (`database/factories/BugFactory.php`):**
   - Generate via `php artisan make:factory BugFactory --model=Bug`.
   - Gunakan helper multibyte-safe (`Illuminate\Support\Str`).
   - Pastikan `module_id` konsisten milik `project_id` yang sama (`fn (array $attributes) => Module::factory()->create(['project_id' => $attributes['project_id']])->id`).
   - Isi default valid: `title` (<= 255), `description`, `reported_by` (<= 150), `reported_date`, `severity` (acak dari `BugSeverity::cases()`), `status` (`BugStatus::Open`), `root_cause` (`null`), `solution_notes` (`null`), `resolved_at` (`null`), `handover_logs` (`[]`).
   - Tambahkan state helper berguna: `critical()`, `inProgress()`, `resolved()` (mengisi `root_cause`, `solution_notes`, dan `resolved_at => now()`), dan `unassigned()` (`assigned_to => null`).
2. **Seeder (`database/seeders/BugSeeder.php`):**
   - Generate via `php artisan make:seeder BugSeeder`.
   - Pastikan data `developers`, `projects`, dan `modules` sudah tersedia (panggil `DeveloperSeeder`, `ProjectSeeder`, `ModuleSeeder` bila belum cukup).
   - Isi minimal 4 contoh bug realistis yang mencakup seluruh `BugSeverity` (`critical`, `high`, `medium`, `low`) dan seluruh `BugStatus` (`open`, `in_progress`, `resolved`), termasuk minimal 1 bug `resolved` dengan `root_cause`, `solution_notes`, dan `resolved_at` terisi, serta minimal 1 bug dengan riwayat JSON `handover_logs`.
   - Gunakan `Bug::updateOrCreate(['project_id' => ..., 'title' => ...], [...])` agar seeder bersifat **idempoten**.
   - Daftarkan `BugSeeder::class` pada `DatabaseSeeder.php` setelah `DeveloperSeeder`, `ProjectSeeder`, dan `ModuleSeeder`.

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
   php artisan db:seed --class=BugSeeder
   php artisan db:seed --class=BugSeeder
   ```
4. Verifikasi struktur, index, dan 4 Foreign Key di MySQL (`SHOW CREATE TABLE bugs;`).

### Tahap 6: Buat Pengujian Otomatis (Feature Test)
1. Buat file `tests/Feature/BugTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `bugs` ada dan memiliki seluruh 17 kolom (15 kolom spesifikasi + `created_at`, `updated_at`).
   - **Default & Nullable:** Membuat bug hanya dengan kolom wajib (`project_id`, `created_by`, `title`) berhasil dengan default `severity = BugSeverity::Medium`, `status = BugStatus::Open`, dan semua kolom opsional bernilai `null`.
   - **Foreign Key Constraint:** Insert `project_id`, `module_id`, `created_by`, atau `assigned_to` yang tidak ada di tabel induk melempar `QueryException`.
   - **Perilaku Delete Foreign Key:**
     - Hapus `Project` -> bug ikut terhapus (*cascade*).
     - Hapus `Module` -> `bug.module_id` berubah menjadi `null` (bug tetap tersimpan).
     - Hapus `Developer` yang hanya menjadi `assigned_to` -> `bug.assigned_to` berubah menjadi `null`.
     - Hapus `Developer` yang tercatat sebagai `created_by` -> ditolak (`QueryException`, *restrict*).
   - **Validasi Enum Negatif:** Nilai `severity` atau `status` tidak valid ditolak di level database (`QueryException`) dan Model cast (`ValueError`).
   - **Casting JSON & Tanggal:** `handover_logs` tersimpan sebagai JSON dan terbaca kembali sebagai `array`; `reported_date` dan `resolved_at` ter-cast menjadi instance `Carbon`.
   - **Relasi Eloquent & Scope:** Seluruh relasi (`project`, `module`, `creator`, `assignee`, `Project::bugs`, `Module::bugs`, `Developer::createdBugs`, `Developer::assignedBugs`) dan scope (`status`, `severity`, `open`, `forProject`, `assignedTo`, termasuk *fail-fast* pada typo) berjalan benar.
   - **Seeder Idempoten:** Menjalankan `BugSeeder` dua kali menghasilkan jumlah record konsisten serta mencakup seluruh `BugSeverity` dan `BugStatus`.
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus tanpa error.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan lupa argumen `'developers'` pada `constrained('developers')`** untuk `created_by` dan `assigned_to`.
- **Jangan lupa foreign key eksplisit pada relasi Model** (`belongsTo(Developer::class, 'created_by')`, `belongsTo(Developer::class, 'assigned_to')`, `hasMany(Bug::class, 'created_by')`, `hasMany(Bug::class, 'assigned_to')`).
- **Jangan pakai huruf kapital pada nama kolom SQL** — wajib `snake_case` huruf kecil.
- **Jangan lupa batas panjang `VARCHAR`**: `title` (`255`) dan `reported_by` (`150`).
- **Jangan impor class Enum di dalam file migration** — gunakan array literal pada `$table->enum(...)`.
- **Jangan memberi default literal pada kolom `JSON` di MySQL** — buat `->nullable()` di migration.
- **Jangan pasang `cascadeOnDelete()` pada `created_by`, `assigned_to`, atau `module_id`** agar riwayat bug tidak ikut terhapus secara tidak sengaja.
- **Jangan jalankan `BugSeeder` sebelum `DeveloperSeeder`, `ProjectSeeder`, dan `ModuleSeeder`**.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Enum `app/Enums/BugSeverity.php` dan `app/Enums/BugStatus.php` dibuat lengkap sesuai spesifikasi.
- [ ] Migration `create_bugs_table` dibuat dengan seluruh 15 kolom, 4 Foreign Key, 2 Enum literal + default, 3 composite index, dan rollback bersih.
- [ ] Model `app/Models/Bug.php` (`$fillable`, `casts()`, 4 relasi `BelongsTo`, query scopes) serta relasi kebalikan di `Project`, `Module`, dan `Developer` telah lengkap.
- [ ] `BugFactory` (dengan state helpers) dan `BugSeeder` (idempoten, mencakup semua `BugSeverity` & `BugStatus`, `root_cause`, `solution_notes`, `resolved_at`, dan `handover_logs`) tersedia serta terdaftar di `DatabaseSeeder.php`.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed --class=BugSeeder` berjalan tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk uji FK, delete behavior, enum negatif, JSON/date cast, relasi, dan seeder idempoten).
