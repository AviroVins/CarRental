<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('cars', function (Blueprint $table) {
            $table->string('plate_number')->primary();
            $table->string('maker');
            $table->string('model');
            $table->integer('year');
            $table->string('status')->default('available');
        });
    }

    public function down(): void {
        Schema::dropIfExists('cars');
    }
};
