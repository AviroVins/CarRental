<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('pesel', 11)->unique()->primary();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('phone_number', 20)->nullable();
            $table->boolean('has_driver_license')->default(true);
            $table->string('account_status', 20)->default('active');
            $table->string('role', 20)->default('user');
            $table->string('profile_photo')->default('def.jpg');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE users ADD CONSTRAINT chk_account_status CHECK (account_status IN ('active', 'inactive'))");
        DB::statement("ALTER TABLE users ADD CONSTRAINT chk_role CHECK (role IN ('user', 'admin'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
