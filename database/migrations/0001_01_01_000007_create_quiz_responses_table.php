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
        Schema::create('quiz_responses', function (Blueprint $table) {
            $table->id();
            $table->uuid('student_id');
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreignId('quiz_template_id')->constrained()->cascadeOnDelete();
            $table->uuid('classroom_id');                       // TRACEABILITY: Classroom at submission time
            $table->foreign('classroom_id')->references('id')->on('classrooms')->cascadeOnDelete();
            $table->text('answers');                            // Encrypted JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_responses');
    }
};
