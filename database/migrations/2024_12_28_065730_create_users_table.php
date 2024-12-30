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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->nullable(false)->unique('users_username_unique');
            $table->string('password', 100)->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->string('email', 100)->nullable(true);
            $table->string('phone', 100)->nullable(true);
            $table->enum('role', ['ADMIN', 'USER'])->default('ADMIN');
            $table->string('token', 100)->nullable()->unique('users_token_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
