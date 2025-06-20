<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id('rental_id');

            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->foreign('reservation_id')->references('reservation_id')->on('reservations')->onDelete('set null');

            $table->string('pesel')->nullable();
            $table->foreign('pesel')->references('pesel')->on('users')->onDelete('set null');

            $table->string('plate_number')->nullable();
            $table->foreign('plate_number')->references('plate_number')->on('cars')->onDelete('set null');

            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('return_time')->nullable();
            $table->integer('distance_km')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rentals');
    }
};