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
        Schema::create('trip_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->references('id')->on('trips')->cascadeOnDelete();
            $table->datetime('start_time');
            $table->double('start_location_x');
            $table->double('start_location_y');
            $table->datetime('stop_time');
            $table->double('stop_location_x');
            $table->double('stop_location_y');
            $table->integer('passenger_count');
            $table->integer('passengers_boarding');
            $table->integer('passengers_alighting');
            $table->integer('is_traffic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_stops');
    }
};
