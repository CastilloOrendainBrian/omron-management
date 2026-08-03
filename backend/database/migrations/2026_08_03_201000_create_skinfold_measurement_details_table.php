<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skinfold_measurement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skinfold_measurement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skinfold_site_id')->constrained('skinfold_sites')->restrictOnDelete();
            $table->decimal('value_mm', 4, 1);

            $table->unique(['skinfold_measurement_id', 'skinfold_site_id']);
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skinfold_measurement_details');
    }
};
