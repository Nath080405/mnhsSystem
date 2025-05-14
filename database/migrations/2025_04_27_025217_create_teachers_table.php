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
        Schema::create('teachers', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->string('street_address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_joined');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
