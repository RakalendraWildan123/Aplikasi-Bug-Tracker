# Planning: Implementasi Tabel `action_items` (Migration, Enum, Model, Relasi, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel **`action_items`** untuk mencatat rencana tindak lanjut (*action plan*) dari hasil evaluasi aplikasi (`app_evaluations`), yang dapat ditugaskan ke developer (`developers`) serta dikonversi menjadi tugas nyata di tabel **`tasks`** pada aplikasi **Laravel 12 + MySQL**, lengkap dengan PHP Backed Enum `ActionItemStatus`, Migration + 3 Foreign Key + Index, Eloquent Model + Relasi dua arah, Factory, Seeder idempoten, dan pengujian otomatis.

Dokumen ini disusun langkah demi langkah agar mudah diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** tanpa terjebak kesalahan penamaan Foreign Key non-standar.

> **Prasyarat Wajib:** Tabel & Model `app_evaluations`, `developers`, dan `tasks` (sudah tersedia di branch `main`) menjadi referensi Foreign Key untuk tabel `action_items`.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Gunakan nama tabel plural **`action_items`** (Model: `App\Models\ActionItem`) agar konsisten dengan seluruh tabel lainnya (`developers`, `projects`, `modules`, `tasks`, `bugs`, `repo_histories`, `app_evaluations`). Seluruh nama kolom **wajib `snake_case` huruf kecil**.  
> *(Jika kebutuhan ERD mewajibkan nama singular `action_item`, ubah nama tabel di migrasi menjadi `action_item` dan tambahkan `protected $table = 'action_item';` pada Model `ActionItem`.)*

| Nama Kolom | Tipe SQL | Panjang / Nilai / Referensi | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik action item |
| `evaluation_id` | `BIGINT UNSIGNED` | FK -> `app_evaluations.id` | `NOT NULL`, `ON DELETE CASCADE` | `$table->foreignId('evaluation_id')->constrained('app_evaluations')->cascadeOnDelete();` | Evaluasi sumber tindak lanjut (wajib eksplisit `'app_evaluations'`) |
| `action_plan` | `TEXT` | - | `NOT NULL` | `$table->text('action_plan');` | Deskripsi rencana tindak lanjut |
| `assigned_to` | `BIGINT UNSIGNED` | FK -> `developers.id` | `NULLABLE`, `ON DELETE SET NULL` | `$table->foreignId('assigned_to')->nullable()->constrained('developers')->nullOnDelete();` | Developer penanggung jawab (wajib eksplisit `'developers'`) |
| `target_date` | `DATE` | - | `NULLABLE` | `$table->date('target_date')->nullable();` | Target tanggal penyelesaian tindak lanjut |
| `status` | `ENUM` | `'planned'`, `'converted_to_task'`, `'completed'` | `NOT NULL`, `DEFAULT 'planned'` | `$table->enum('status', ['planned', 'converted_to_task', 'completed'])->default('planned');` | Status tindak lanjut |
| `converted_task_id` | `BIGINT UNSIGNED` | FK -> `tasks.id` | `NULLABLE`, `ON DELETE SET NULL` | `$table->foreignId('converted_task_id')->nullable()->constrained('tasks')->nullOnDelete();` | Referensi ke task hasil konversi (wajib eksplisit `'tasks'`) |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

**Index Tambahan:**
- `$table->index(['evaluation_id', 'status']);` — memfilter tindak lanjut per evaluasi berdasarkan status.
- `$table->index(['assigned_to', 'status']);` — memantau tindak lanjut yang ditugaskan ke developer tertentu.

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat PHP Backed Enum (`ActionItemStatus`)
1. Buat `app/Enums/ActionItemStatus.php` (`enum ActionItemStatus: string`) dengan 3 case persis:
   - `Planned = 'planned'`
   - `ConvertedToTask = 'converted_to_task'`
   - `Completed = 'completed'`
2. Tambahkan static method `values(): array` (`array_column(self::cases(), 'value')`).

### Tahap 2: Buat File Migration Tabel `action_items`
1. Pastikan file migrasi `create_app_evaluations_table`, `create_developers_table`, dan `create_tasks_table` sudah ada dan timestamp migrasi baru ini **lebih baru** dari ketiganya.
2. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_action_items_table
   ```
3. Pada method `up()`, buat tabel `action_items` sesuai tabel spesifikasi di atas:
   - **Perhatian khusus Foreign Key:** Ketiga kolom FK memiliki nama non-standar sehingga **semua wajib** menyebutkan nama tabel target secara eksplisit:
     - `->constrained('app_evaluations')->cascadeOnDelete()` untuk `evaluation_id`
     - `->constrained('developers')->nullOnDelete()` untuk `assigned_to`
     - `->constrained('tasks')->nullOnDelete()` untuk `converted_task_id`
   - **Hardcode array literal** `['planned', 'converted_to_task', 'completed']` di `$table->enum('status', ...)` (jangan impor class Enum di file migration).
   - Jadikan `assigned_to`, `target_date`, dan `converted_task_id` bersifat `->nullable()`.
   - Tambahkan composite index `['evaluation_id', 'status']` dan `['assigned_to', 'status']`.
   - Sertakan `$table->timestamps();`.
4. Pada method `down()`, pastikan berisi `Schema::dropIfExists('action_items');`.

### Tahap 3: Buat Eloquent Model `ActionItem` & Lengkapi Relasi di Model Lain
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model ActionItem
   ```
2. Di `app/Models/ActionItem.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`: `['evaluation_id', 'action_plan', 'assigned_to', 'target_date', 'status', 'converted_task_id']`.
   - Definisikan method `casts(): array` (konvensi Laravel 12):
     - `'status' => ActionItemStatus::class`
     - `'target_date' => 'date'`
   - Definisikan 3 relasi `BelongsTo` dengan **foreign key eksplisit**:
     - `evaluation(): BelongsTo` -> `$this->belongsTo(AppEvaluation::class, 'evaluation_id')`
     - `assignee(): BelongsTo` -> `$this->belongsTo(Developer::class, 'assigned_to')`
     - `convertedTask(): BelongsTo` -> `$this->belongsTo(Task::class, 'converted_task_id')`
   - Tambahkan query scope umum (*fail-fast* dengan `ActionItemStatus::from(...)` bila parameter string):
     - `scopeStatus(Builder $query, ActionItemStatus|string $status): Builder`
     - `scopeForEvaluation(Builder $query, int $evaluationId): Builder`
     - `scopeAssignedTo(Builder $query, int $developerId): Builder`
3. Lengkapi relasi kebalikan pada Model terkait (semua dengan foreign key eksplisit!):
   - `app/Models/AppEvaluation.php` -> `actionItems(): HasMany` (`$this->hasMany(ActionItem::class, 'evaluation_id')`)
   - `app/Models/Developer.php` -> `assignedActionItems(): HasMany` (`$this->hasMany(ActionItem::class, 'assigned_to')`)
   - `app/Models/Task.php` -> `sourceActionItem(): HasOne` (`$this->hasOne(ActionItem::class, 'converted_task_id')`)

### Tahap 4: Buat Factory (`ActionItemFactory`) dan Seeder (`ActionItemSeeder`)
1. **Factory (`database/factories/ActionItemFactory.php`):**
   - Generate via `php artisan make:factory ActionItemFactory --model=ActionItem`.
   - Definisikan atribut default valid:
     - `'evaluation_id' => AppEvaluation::factory()`
     - `'action_plan' => fake()->sentence(10)`
     - `'assigned_to' => Developer::factory()`
     - `'target_date' => now()->addDays(fake()->numberBetween(3, 30))->toDateString()`
     - `'status' => ActionItemStatus::Planned`
     - `'converted_task_id' => null`
   - Tambahkan state helper berguna: `convertedToTask(?Task $task = null)` (set `status => ActionItemStatus::ConvertedToTask` dan `converted_task_id => $task?->id ?? Task::factory()`), `completed()`, dan `unassigned()`.
2. **Seeder (`database/seeders/ActionItemSeeder.php`):**
   - Generate via `php artisan make:seeder ActionItemSeeder`.
   - Pastikan data `developers`, `app_evaluations`, dan `tasks` sudah tersedia (panggil `DeveloperSeeder`, `AppEvaluationSeeder`, dan `TaskSeeder` bila belum cukup).
   - Isi minimal 3–4 data tindak lanjut realistis yang mencakup seluruh `ActionItemStatus` (`planned`, `converted_to_task` dengan `converted_task_id` merujuk ke `Task` nyata, dan `completed`).
   - Gunakan `ActionItem::updateOrCreate(['evaluation_id' => ..., 'action_plan' => ...], [...])` agar seeder bersifat **idempoten**.
   - Daftarkan `ActionItemSeeder::class` pada `DatabaseSeeder.php` di urutan paling akhir (setelah `DeveloperSeeder`, `TaskSeeder`, dan `AppEvaluationSeeder`).

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
   php artisan db:seed --class=ActionItemSeeder
   php artisan db:seed --class=ActionItemSeeder
   ```
4. Verifikasi struktur tabel, index, dan 3 Foreign Key di MySQL (`SHOW CREATE TABLE action_items;`).

### Tahap 6: Buat Pengujian Otomatis (Feature Test)
1. Buat file `tests/Feature/ActionItemTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `action_items` ada dan memiliki seluruh 9 kolom (`id`, `evaluation_id`, `action_plan`, `assigned_to`, `target_date`, `status`, `converted_task_id`, `created_at`, `updated_at`).
   - **Default & Nullable:** Membuat `ActionItem` hanya dengan kolom wajib (`evaluation_id`, `action_plan`) berhasil dengan default `status = ActionItemStatus::Planned` serta `assigned_to`, `target_date`, dan `converted_task_id` bernilai `null`.
   - **Foreign Key Constraint:** Insert `evaluation_id`, `assigned_to`, atau `converted_task_id` yang tidak ada di tabel induk melempar `QueryException`.
   - **Perilaku Delete Foreign Key:**
     - Hapus `Developer` assignee -> `action_item.assigned_to` menjadi `null`.
     - Hapus `Task` terkait -> `action_item.converted_task_id` menjadi `null`.
     - Hapus `AppEvaluation` -> `action_item` ikut terhapus (*cascade*).
   - **Validasi Enum Negatif:** Nilai `status` tidak valid ditolak di level database (`QueryException`) dan Model cast (`ValueError`).
   - **Casting, Relasi Dua Arah & Scope:** `target_date` ter-cast menjadi `Carbon`, `status` menjadi `ActionItemStatus`, seluruh relasi (`evaluation`, `assignee`, `convertedTask`, `AppEvaluation::actionItems`, `Developer::assignedActionItems`, `Task::sourceActionItem`) serta scopes (`status`, `forEvaluation`, `assignedTo`, termasuk *fail-fast* pada typo) berjalan benar.
   - **Seeder Idempoten:** Menjalankan `ActionItemSeeder` dua kali menghasilkan jumlah record konsisten, mencakup seluruh 3 status `ActionItemStatus`, dan memiliki minimal 1 record dengan `converted_task_id` terisi.
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus tanpa error.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan lupa nama tabel eksplisit pada ketiga `constrained(...)`**: `'app_evaluations'`, `'developers'`, dan `'tasks'` — tanpa argumen ini Laravel akan mencari tabel `evaluations`, `assigned_tos`, dan `converted_tasks` yang tidak ada.
- **Jangan lupa foreign key eksplisit pada semua relasi Eloquent**, termasuk `$this->hasMany(ActionItem::class, 'evaluation_id')` di `AppEvaluation.php` (karena default Laravel mencari `app_evaluation_id`).
- **Jangan pakai huruf kapital pada nama kolom SQL** — wajib `snake_case` huruf kecil.
- **Jangan impor class Enum di dalam file migration** — gunakan array literal `['planned', 'converted_to_task', 'completed']`.
- **Jangan jalankan `ActionItemSeeder` sebelum `DeveloperSeeder`, `TaskSeeder`, dan `AppEvaluationSeeder`**.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Enum `app/Enums/ActionItemStatus.php` dibuat dengan 3 status (`planned`, `converted_to_task`, `completed`).
- [ ] Migration `create_action_items_table` dibuat dengan seluruh 7 kolom + `timestamps()`, 3 Foreign Key (`app_evaluations` cascade, `developers` nullOnDelete, `tasks` nullOnDelete), Enum literal + default `'planned'`, 2 composite index, dan rollback bersih.
- [ ] Model `app/Models/ActionItem.php` (`$fillable`, `casts()`, 3 relasi `BelongsTo`, query scopes) serta relasi kebalikan di `AppEvaluation`, `Developer`, dan `Task` telah lengkap.
- [ ] `ActionItemFactory` (dengan state `convertedToTask`, `completed`, `unassigned`) dan `ActionItemSeeder` (idempoten, mencakup semua status + relasi ke `Task`) tersedia serta terdaftar di urutan terakhir `DatabaseSeeder.php`.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed --class=ActionItemSeeder` berjalan tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk uji FK, delete behavior, enum negatif, casting tanggal/enum, relasi dua arah, scope, dan seeder idempoten).
