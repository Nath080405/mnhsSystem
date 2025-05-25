<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events', 'event_id')->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_views');
    }
}; 