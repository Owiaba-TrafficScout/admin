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
            $table->datetime('start_time')->nullable();
            $table->double('start_location_x')->nullable();
            $table->double('start_location_y')->nullable();
            $table->datetime('stop_time')->nullable();
            $table->double('stop_location_x')->nullable();
            $table->double('stop_location_y')->nullable();
            $table->integer('passengers_count')->nullable();
            $table->integer('passengers_boarding')->nullable();
            $table->integer('passengers_alighting')->nullable();
            $table->boolean('is_traffic')->nullable();
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
