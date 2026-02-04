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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Who performed action
            $table->string('user_name');                        // Denormalized for historical record
            $table->string('action');                           // e.g., 'viewed', 'created', 'updated', 'deleted'
            $table->string('subject_type');                     // e.g., 'student', 'quiz_response', 'classroom'
            $table->string('subject_id')->nullable();           // UUID or ID of the subject
            $table->string('subject_name')->nullable();         // Denormalized name for readability
            $table->text('description');                        // Human-readable description
            $table->json('metadata')->nullable();               // Additional context (IP, user-agent, etc.)
            $table->timestamp('created_at');

            $table->index(['school_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
