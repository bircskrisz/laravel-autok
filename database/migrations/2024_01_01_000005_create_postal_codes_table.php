<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->char('postal_code', 4)->index();
            $table->string('city', 100)->index();
            $table->foreignId('county_id')->constrained('counties')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['postal_code', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
