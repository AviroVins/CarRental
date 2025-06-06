<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');

            $table->unsignedBigInteger('rental_id');
            $table->foreign('rental_id')->references('rental_id')->on('rentals')->onDelete('cascade');

            $table->string('pesel');
            $table->foreign('pesel')->references('pesel')->on('users')->onDelete('cascade');

            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('method');
        });
    }
    
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};