<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturer_id')->constrained('manufacturers')->onDelete('cascade');
            $table->string('name', 150);
            $table->string('model_code', 30)->unique();
            $table->unsignedSmallInteger('model_year');
            $table->decimal('price', 12, 2);
            $table->string('body_type', 50)->nullable();
            $table->string('fuel_type', 50)->nullable();
            $table->unsignedSmallInteger('power_hp')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
