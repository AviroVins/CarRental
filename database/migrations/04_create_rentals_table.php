<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id('rental_id');
            
            $table->unsignedBigInteger('reservation_id');
            $table->foreign('reservation_id')->references('reservation_id')->on('reservations')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->string('plate_number');
            $table->foreign('plate_number')->references('plate_number')->on('cars')->onDelete('cascade');

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