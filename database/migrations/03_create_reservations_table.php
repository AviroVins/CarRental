<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');

            $table->string('pesel');
            $table->foreign('pesel')->references('pesel')->on('users')->onDelete('cascade');
           
            $table->string('plate_number');
            $table->foreign('plate_number')->references('plate_number')->on('cars')->onDelete('cascade');

            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('status')->default('reserved');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reservations');
    }
};