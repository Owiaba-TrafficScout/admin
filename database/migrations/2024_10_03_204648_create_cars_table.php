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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_type_id')->references('id')->on('car_types')->constrained()->cascadeOnDelete();
            $table->foreignId('car_status_id')->references('id')->on('car_statuses')->constrained()->cascadeOnDelete();
            $table->string('car_number');
            $table->date('last_maintenance_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
