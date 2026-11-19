<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();
            $table->foreignId('module_id')
                ->nullable()
                ->constrained('modules')
                ->nullOnDelete();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('reported_by', 150)->nullable();
            $table->date('reported_date')->nullable();
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])
                ->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved'])
                ->default('open');
            $table->text('root_cause')->nullable();
            $table->text('solution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('handover_logs')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'status']);
            $table->index(['severity', 'status']);
            $table->index(['assigned_to', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
