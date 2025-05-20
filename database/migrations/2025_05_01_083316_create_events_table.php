<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->enum('visibility', ['All', 'Students', 'Teachers'])->default('All');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Upcoming', 'Completed', 'Cancelled'])->default('Upcoming');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
