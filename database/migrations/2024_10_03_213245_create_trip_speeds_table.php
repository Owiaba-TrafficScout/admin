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
        Schema::create('trip_speeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->references('id')->on('trips')->cascadeOnDelete();
            $table->double('location_x');
            $table->double('location_y');
            $table->double('velocity');
            $table->boolean('is_traffic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_speeds');
    }
};
