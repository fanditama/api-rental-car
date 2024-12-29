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
            $table->string('name', 100)->nullable(false);
            $table->string('brand', 100)->nullable(false);
            $table->string('model', 100)->nullable(false);
            $table->integer('year')->nullable(false);
            $table->string('color', 100)->nullable(false);
            $table->string('image')->nullable(false);
            $table->enum('transmision', ['AUTOMATIC', 'MANUAL']);
            $table->integer('seat')->nullable(false);
            $table->decimal('cost_per_day')->default(0.00);
            $table->longText('location')->nullable(true);
            $table->boolean('available')->default(true);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();
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
