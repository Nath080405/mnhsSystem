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
        Schema::table('events', function (Blueprint $table) {
            $table->time('event_time')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_read')->default(false);
            $table->string('venue_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'event_time',
                'location',
                'is_urgent',
                'is_read',
                'venue_image'
            ]);
        });
    }
};
