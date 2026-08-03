<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anthropometric_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurement_session_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('height_cm', 5, 2);
            $table->decimal('weight_kg', 6, 3);
            $table->decimal('bmi', 4, 1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anthropometric_measurements');
    }
};
