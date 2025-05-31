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
        Schema::create('automatic_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students', 'user_id')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('grade_level');
            $table->timestamps();

            // Ensure a student can only be automatically enrolled once per subject
            $table->unique(['student_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automatic_enrollments');
    }
}; 