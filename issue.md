# Planning: Implementasi Tabel `tasks` (Migration, Enum, Model, Relasi, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel inti **`tasks`** untuk mencatat dan memantau pekerjaan/tugas developer yang terhubung ke tabel **`projects`**, **`modules`**, dan **`developers`** pada aplikasi **Laravel 12 + MySQL**, lengkap dengan 2 PHP Backed Enum (`TaskType` & `TaskStatus`), Migration + Foreign Key + Index, Eloquent Model + Relasi dua arah, Factory, Seeder idempoten, dan pengujian otomatis.

Dokumen ini disusun secara terstruktur langkah demi langkah agar mudah diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** tanpa kesalahan pada relasi Foreign Key maupun casting JSON/Enum/Date.

> **Prasyarat Wajib:** Pastikan tabel & Model `developers` (Issue #3), `projects` (Issue #5), dan `modules` (Issue #7 / PR #8) sudah ter-merge dan tersedia di branch kerja sebelum menjalankan migrasi ini.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Gunakan nama tabel plural Bahasa Inggris **`tasks`** (Model: `Task`) agar konsisten dengan `users`, `developers`, `projects`, dan `modules`. Seluruh nama kolom **wajib `snake_case` huruf kecil**.  
> *(Jika kebutuhan ERD mewajibkan nama singular `task`, ubah nama tabel di migrasi menjadi `task` dan tambahkan `protected $table = 'task';` pada Model `Task`.)*

| Nama Kolom | Tipe SQL | Panjang / Nilai / Referensi | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik task |
| `project_id` | `BIGINT UNSIGNED` | FK -> `projects.id` | `NOT NULL`, `ON DELETE CASCADE` | `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();` | Project pemilik task |
| `module_id` | `BIGINT UNSIGNED` | FK -> `modules.id` | `NULLABLE`, `ON DELETE SET NULL` | `$table->foreignId('module_id')->nullable()->constrained('modules')->nullOnDelete();` | Modul terkait (opsional / aman jika modul dihapus) |
| `created_by` | `BIGINT UNSIGNED` | FK -> `developers.id` | `NOT NULL`, `ON DELETE RESTRICT` | `$table->foreignId('created_by')->constrained('developers')->restrictOnDelete();` | Developer pembuat task (wajib eksplisit `'developers'`) |
| `assigned_to` | `BIGINT UNSIGNED` | FK -> `developers.id` | `NULLABLE`, `ON DELETE SET NULL` | `$table->foreignId('assigned_to')->nullable()->constrained('developers')->nullOnDelete();` | Developer penanggung jawab (boleh kosong saat `todo`) |
| `title` | `VARCHAR` | `255` | `NOT NULL` | `$table->string('title', 255);` | Judul singkat task |
| `description` | `TEXT` | - | `NULLABLE` | `$table->text('description')->nullable();` | Penjelasan detail task |
| `task_type` | `ENUM` | `'feature'`, `'enhancement'`, `'refactor'`, `'maintenance'` | `NOT NULL`, `DEFAULT 'feature'` | `$table->enum('task_type', ['feature', 'enhancement', 'refactor', 'maintenance'])->default('feature');` | Jenis pekerjaan |
| `status` | `ENUM` | `'todo'`, `'in_progress'`, `'review'`, `'done'` | `NOT NULL`, `DEFAULT 'todo'` | `$table->enum('status', ['todo', 'in_progress', 'review', 'done'])->default('todo');` | Status board Kanban |
| `start_date` | `DATE` | - | `NULLABLE` | `$table->date('start_date')->nullable();` | Tanggal mulai pengerjaan |
| `target_date` | `DATE` | - | `NULLABLE` | `$table->date('target_date')->nullable();` | Target tanggal selesai (deadline) |
| `completed_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamp('completed_at')->nullable();` | Waktu aktual task selesai (`done`) |
| `blocker_notes` | `TEXT` | - | `NULLABLE` | `$table->text('blocker_notes')->nullable();` | Catatan kendala/blocker jika ada |
| `handover_logs` | `JSON` | Array of objects | `NULLABLE` | `$table->json('handover_logs')->nullable();` | Riwayat serah terima antar developer |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

**Index Tambahan (untuk performa Board Kanban & Monitoring):**
- `$table->index(['project_id', 'status']);` — mempercepat query daftar task per kolom status dalam satu project.
- `$table->index(['assigned_to', 'status']);` — mempercepat query beban kerja task aktif per developer.

**Konvensi Struktur JSON untuk `handover_logs`:**
```json
[
  {
    "from_developer_id": 1,
    "to_developer_id": 2,
    "note": "Serah terima endpoint API setelah integrasi awal selesai.",
    "handed_over_at": "2026-11-18T10:00:00Z"
  }
]
```

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat 2 PHP Backed Enum (`TaskType` & `TaskStatus`)
1. Buat `app/Enums/TaskType.php` (`enum TaskType: string`) dengan 4 case persis sesuai spesifikasi:
   - `Feature = 'feature'`
   - `Enhancement = 'enhancement'`
   - `Refactor = 'refactor'`
   - `Maintenance = 'maintenance'`
   - Tambahkan static method `values(): array`.
2. Buat `app/Enums/TaskStatus.php` (`enum TaskStatus: string`) dengan 4 case persis sesuai spesifikasi:
   - `Todo = 'todo'`
   - `InProgress = 'in_progress'`
   - `Review = 'review'`
   - `Done = 'done'`
   - Tambahkan static method `values(): array`.

### Tahap 2: Buat File Migration Tabel `tasks`
1. Pastikan file migrasi `create_developers_table`, `create_projects_table`, dan `create_modules_table` sudah ada dan timestamp migrasi baru ini **lebih baru** dari ketiganya.
2. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_tasks_table
   ```
3. Pada method `up()`, definisikan seluruh kolom pada tabel spesifikasi di atas:
   - **Perhatian khusus Foreign Key:** Untuk `created_by` dan `assigned_to`, **wajib** menuliskan nama tabel `'developers'` secara eksplisit di `->constrained('developers')` (karena nama kolom bukan `developer_id`).
   - **Hardcode array literal** pada kedua kolom `$table->enum(...)` (jangan impor class Enum di file migration).
   - Jadikan kolom `module_id`, `assigned_to`, `description`, `start_date`, `target_date`, `completed_at`, `blocker_notes`, dan `handover_logs` bersifat `->nullable()`.
   - Tambahkan composite index `['project_id', 'status']` dan `['assigned_to', 'status']`.
   - Sertakan `$table->timestamps();`.
4. Pada method `down()`, pastikan berisi `Schema::dropIfExists('tasks');`.

### Tahap 3: Buat Eloquent Model `Task` & Lengkapi Relasi di Model Lain
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model Task
   ```
2. Di `app/Models/Task.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`:
     `['project_id', 'module_id', 'created_by', 'assigned_to', 'title', 'description', 'task_type', 'status', 'start_date', 'target_date', 'completed_at', 'blocker_notes', 'handover_logs']`.
   - Definisikan method `casts(): array` (konvensi Laravel 12):
     - `'task_type' => TaskType::class`
     - `'status' => TaskStatus::class`
     - `'start_date' => 'date'`
     - `'target_date' => 'date'`
     - `'completed_at' => 'datetime'`
     - `'handover_logs' => 'array'`
   - Definisikan 4 relasi `BelongsTo`:
     - `project(): BelongsTo` -> `$this->belongsTo(Project::class)`
     - `module(): BelongsTo` -> `$this->belongsTo(Module::class)`
     - `creator(): BelongsTo` -> `$this->belongsTo(Developer::class, 'created_by')`
     - `assignee(): BelongsTo` -> `$this->belongsTo(Developer::class, 'assigned_to')`
   - Tambahkan query scope umum:
     - `scopeStatus(Builder $query, TaskStatus|string $status): Builder` (validasi string via `TaskStatus::from($status)` agar *fail-fast* bila typo)
     - `scopeType(Builder $query, TaskType|string $type): Builder` (validasi string via `TaskType::from($type)`)
     - `scopeForProject(Builder $query, int $projectId): Builder`
     - `scopeAssignedTo(Builder $query, int $developerId): Builder`
3. Lengkapi relasi kebalikan (`HasMany`) pada Model terkait:
   - `app/Models/Project.php` -> `tasks(): HasMany`
   - `app/Models/Module.php` -> `tasks(): HasMany`
   - `app/Models/Developer.php` -> `createdTasks(): HasMany` (`'created_by'`) dan `assignedTasks(): HasMany` (`'assigned_to'`)

### Tahap 4: Buat Factory (`TaskFactory`) dan Seeder (`TaskSeeder`)
1. **Factory (`database/factories/TaskFactory.php`):**
   - Generate via `php artisan make:factory TaskFactory --model=Task`.
   - Gunakan helper multibyte-safe (`Illuminate\Support\Str`).
   - Pastikan `module_id` konsisten milik `project_id` yang sama (contoh: gunakan closure `fn (array $attributes) => Module::factory()->create(['project_id' => $attributes['project_id']])->id`).
   - Isi nilai default valid (`title` <= 255 karakter, `task_type` acak dari `TaskType::cases()`, `status` default `TaskStatus::Todo`, `start_date`, `target_date` >= `start_date`, `completed_at` = `null`, `blocker_notes` = `null`, `handover_logs` = `[]`).
   - Tambahkan state helper berguna: `inProgress()`, `inReview()`, `done()` (set `completed_at => now()`), `unassigned()` (`assigned_to => null`), dan `blocked(string $note)`.
2. **Seeder (`database/seeders/TaskSeeder.php`):**
   - Generate via `php artisan make:seeder TaskSeeder`.
   - Pastikan data `developers`, `projects`, dan `modules` sudah tersedia (panggil `DeveloperSeeder`, `ProjectSeeder`, `ModuleSeeder` bila belum ada).
   - Isi minimal 4 data contoh realistis yang mencakup seluruh `TaskType` (`feature`, `enhancement`, `refactor`, `maintenance`) dan seluruh `TaskStatus` (`todo`, `in_progress`, `review`, `done`), termasuk minimal 1 task dengan `blocker_notes` dan 1 task dengan riwayat JSON `handover_logs`.
   - Gunakan `Task::updateOrCreate(['project_id' => ..., 'title' => ...], [...])` agar seeder bersifat **idempoten**.
   - Daftarkan `TaskSeeder::class` pada `DatabaseSeeder.php` **setelah** `DeveloperSeeder`, `ProjectSeeder`, dan `ModuleSeeder`.

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
   php artisan db:seed --class=TaskSeeder
   php artisan db:seed --class=TaskSeeder
   ```
4. Verifikasi struktur & seluruh Foreign Key di MySQL (`SHOW CREATE TABLE tasks;`).

### Tahap 6: Buat Pengujian Otomatis (Feature Test)
1. Buat file `tests/Feature/TaskTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `tasks` ada dan memiliki seluruh 16 kolom (14 kolom spesifikasi + `created_at`, `updated_at`).
   - **Default & Nullable:** Membuat task hanya dengan kolom wajib (`project_id`, `created_by`, `title`) berhasil dengan default `task_type = TaskType::Feature`, `status = TaskStatus::Todo`, dan semua kolom opsional bernilai `null`.
   - **Foreign Key Constraint:** Insert `project_id`, `module_id`, `created_by`, atau `assigned_to` yang tidak ada di tabel induk melempar `QueryException`.
   - **Perilaku Delete Foreign Key:**
     - Hapus `Project` -> task ikut terhapus (*cascade*).
     - Hapus `Module` -> `task.module_id` berubah menjadi `null` (task tetap ada).
     - Hapus `Developer` yang hanya menjadi `assigned_to` -> `task.assigned_to` berubah menjadi `null`.
     - Hapus `Developer` yang tercatat sebagai `created_by` -> ditolak (`QueryException`, *restrict*).
   - **Validasi Enum Negatif:** Nilai `task_type` atau `status` tidak valid ditolak di level database (`QueryException`) dan Model cast (`ValueError`).
   - **Casting JSON & Tanggal:** `handover_logs` tersimpan sebagai JSON dan terbaca kembali sebagai `array`; `start_date`, `target_date`, dan `completed_at` ter-cast menjadi instance `Carbon`.
   - **Relasi Eloquent & Scope:** Semua relasi (`project`, `module`, `creator`, `assignee`, `Project::tasks`, `Module::tasks`, `Developer::createdTasks`, `Developer::assignedTasks`) dan scope (`status`, `type`, `forProject`, `assignedTo`) berjalan benar.
   - **Seeder Idempoten:** Menjalankan `TaskSeeder` dua kali menghasilkan jumlah record konsisten serta mencakup seluruh nilai `TaskType` dan `TaskStatus`.
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus tanpa error.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan lupa argumen `'developers'` pada `constrained('developers')`** untuk `created_by` dan `assigned_to` — tanpa argumen ini Laravel akan mencari tabel `created_bies` / `assigned_tos` dan migrasi akan error.
- **Jangan lupa foreign key eksplisit pada relasi Model** (`belongsTo(Developer::class, 'created_by')`, `belongsTo(Developer::class, 'assigned_to')`, `hasMany(Task::class, 'created_by')`, `hasMany(Task::class, 'assigned_to')`).
- **Jangan pakai huruf kapital pada nama kolom SQL** — wajib `snake_case` huruf kecil.
- **Jangan impor class Enum di dalam file migration** — gunakan array literal pada `$table->enum(...)`.
- **Jangan memberi default literal pada kolom `JSON` di MySQL** — buat `->nullable()` di migration agar kompatibel di semua versi MySQL.
- **Jangan pasang `cascadeOnDelete()` pada `created_by` / `assigned_to` / `module_id`** agar penghapusan developer atau modul tidak ikut menghapus riwayat pekerjaan secara tidak sengaja.
- **Jangan jalankan `TaskSeeder` sebelum `DeveloperSeeder`, `ProjectSeeder`, dan `ModuleSeeder`**.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Enum `app/Enums/TaskType.php` dan `app/Enums/TaskStatus.php` dibuat lengkap dengan nilai sesuai spesifikasi.
- [ ] Migration `create_tasks_table` dibuat dengan seluruh 14 kolom, 4 Foreign Key (`projects`, `modules`, `developers` x2), 2 Enum literal + default, 2 composite index, dan rollback bersih.
- [ ] Model `app/Models/Task.php` (`$fillable`, `casts()`, 4 relasi `BelongsTo`, query scopes) serta relasi kebalikan di `Project`, `Module`, dan `Developer` telah lengkap.
- [ ] `TaskFactory` (dengan state helpers) dan `TaskSeeder` (idempoten, mencakup semua `TaskType` & `TaskStatus`, `blocker_notes`, dan `handover_logs`) tersedia dan terdaftar di urutan terakhir `DatabaseSeeder.php`.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed --class=TaskSeeder` berjalan tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk uji FK, delete behavior, enum negatif, JSON/date cast, relasi, dan seeder idempoten).
