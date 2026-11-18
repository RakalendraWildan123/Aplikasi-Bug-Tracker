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
        Schema::create('action_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')
                ->constrained('app_evaluations')
                ->cascadeOnDelete();
            $table->text('action_plan');
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('developers')
                ->nullOnDelete();
            $table->date('target_date')->nullable();
            $table->enum('status', ['planned', 'converted_to_task', 'completed'])
                ->default('planned');
            $table->foreignId('converted_task_id')
                ->nullable()
                ->constrained('tasks')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['evaluation_id', 'status']);
            $table->index(['assigned_to', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_items');
    }
};
