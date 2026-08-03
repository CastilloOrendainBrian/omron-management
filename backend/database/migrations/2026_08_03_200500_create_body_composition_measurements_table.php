<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('body_composition_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurement_session_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('body_fat_percentage', 4, 1)->nullable();
            $table->decimal('muscle_percentage', 4, 1)->nullable();
            $table->integer('visceral_fat_level')->nullable();
            $table->integer('metabolic_age')->nullable();
            $table->integer('bmr_kcal')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_composition_measurements');
    }
};
